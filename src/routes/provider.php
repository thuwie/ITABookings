<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Inbound\InformationPaymentServicePort;
use App\Application\Port\Inbound\AdminServicePort;
use App\Middleware\AuthMiddleware;

return function (App $app, $twig) {

    $app->group('/provider', function (RouteCollectorProxy $group) use ($twig) {

        /** -----------------------------------------
         *  Resolve dependencies ONCE per route group
         * ----------------------------------------- */
        $container = $group->getContainer();

        $provinceService = $container->get(ProvinceServicePort::class);
        $providerService = $container->get(ProviderServicePort::class);
        $paymentService  = $container->get(InformationPaymentServicePort::class);
        $adminServices  = $container->get(AdminServicePort::class);

        /** ---------------------------
         * GET /provider/register
         * --------------------------- */
        $group->get('/register', function ($request, $response) 
            use ($twig, $provinceService) {

            $provinces = $provinceService->getProvinces();

            $html = $twig->render('pages/provider/pro_register.html.twig', [
                'provinces' => $provinces
            ]);

            $response->getBody()->write($html);
            return $response;
        });

         /** ---------------------------
         * GET /provider/register-form
         * --------------------------- */
        $group->get('/register-form-detail', function ($request, $response) 
            use ($twig, $providerService, $provinceService) { 
            
            $registeredInformation = $providerService->getRegisterForm();
            $provinces = $provinceService->getProvinces();

            $html = $twig->render('pages/provider/register.form.detail.html.twig', [
                'information' => $registeredInformation,
                'provinces' => $provinces
            ]);

            $response->getBody()->write($html);
            return $response;
        });

         /** ---------------------------
         * GET /provider/register-vehicles
         * --------------------------- */        
        $group->get('/register-vehicles', function ($request, $response) 
            use ($twig, $providerService, $provinceService) { 
            
            $utilities = $providerService->getUtilities();
            $html = $twig->render('pages/provider/vehicle.register.html.twig', [
                'utilities' => $utilities
            ]);

            $response->getBody()->write($html);
            return $response;
        });

         /** ---------------------------
         * GET /provider/utilities
         * --------------------------- */        
        $group->get('/utilities', function ($request, $response) 
            use ($providerService) { 
            
            $utilities = $providerService->getUtilities();
            $payload = [
                'status' => 'success',
                'data'   => $utilities,
            ];

            $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json');
        });


        /** ---------------------------
         * GET /provider/extra-costs-form
         * --------------------------- */
        $group->get('/{id}/extra-costs', function ($request, $response) 
            use ($twig, $adminServices) {
            
            $extraCosts = $adminServices->getExtraCost();

              $html = $twig->render('pages/provider/costs_related_providers.html.twig', ['extraCosts' => $extraCosts]);



            $response->getBody()->write($html);
            return $response;
        });


         /** ---------------------------
         * POST /provider/extra-costs-form
         * --------------------------- */
        $group->post('/{id}/extra-costs', function ($request, $response, $args) use ($providerService) {

        $id = $args['id'];

        // Read JSON body
        $rawBody = $request->getBody()->getContents();
        $body = json_decode($rawBody, true); 

        if ($body === null) {
    
            $payload = [
                'status' => 'error',
                'message' => 'Invalid JSON payload'
            ];
            $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json');
        }

        try {
           
            $providerService->saveProviderExtraCosts($body, $id);

            $payload = [
                'status'  => 'success',
                'message' => 'Chi phí đã được lưu',
                'redirect' => '/'
            ];

        } catch (\Exception $e) {
            $payload = [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }

        // Return JSON
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    });


        /** ---------------------------
         * POST /provider/register
         * --------------------------- */
        $group->post('/register', function ($request, $response) 
            use ($providerService, $paymentService) {

            try {
                $body = $request->getParsedBody();

                $providerInfo = json_decode($body['provider-information'], true);
                $paymentInfo  = json_decode($body['payment-information'], true);

                // Uploaded files
                $files = $request->getUploadedFiles();
                $logo  = $files['logo'] ?? null;
                $qr    = $files['qr'] ?? null;

                // Save provider
                $resultPro = $providerService->save($providerInfo, $logo);

                if ($resultPro) {
                    $resultPayment = $paymentService->save($paymentInfo, $qr);

                    $payload = [
                        'status'  => $resultPayment ? 'success' : 'error',
                        'message' => $resultPayment
                            ? 'Đăng ký doanh nghiệp thành công'
                            : 'Đăng ký doanh nghiệp thất bại',
                    ];
                } else {
                    $payload = [
                        'status'  => 'error',
                        'message' => 'Đăng ký doanh nghiệp thất bại',
                    ];
                }

            } catch (\Exception $e) {
                $payload = [
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ];
            }

            $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json');
        });

         /** ---------------------------
         * POST /provider/{id}/vehicles
         * --------------------------- */        
       $group->post('/{id}/vehicles', function ($request, $response) use ($twig, $providerService) {

        try {
            $body = $request->getParsedBody();

            $vehicleInfo = json_decode($body['vehicle-information'], true);

            // Uploaded files
            $files = $request->getUploadedFiles();
            $imgs  = $files['files'] ?? [];

            $id = $request->getAttribute('id');

            // Save vehicle
            $resultPro = $providerService->saveVehicle($vehicleInfo, $imgs, $id);

            // Build payload
            if ($resultPro) {
                $payload = [
                    'status'  => 'success',
                    'message' => 'Đăng ký phương tiện thành công'
                ];
            } else {
                $payload = [
                    'status'  => 'error',
                    'message' => 'Đăng ký phương tiện thất bại',
                ];
            }

        } catch (\Exception $e) {
            $payload = [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }

        // Write JSON to response
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    });


    })->add(new AuthMiddleware());

};
