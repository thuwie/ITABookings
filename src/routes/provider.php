<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Inbound\InformationPaymentServicePort;
use App\Application\Port\Inbound\AdminServicePort;
use App\Middleware\AuthMiddleware;
use App\Middleware\AuthorizationMiddleware;

return function (App $app, $twig) {

     /** ---------------------------
         * GET /provider/register
         * --------------------------- */
        $app->get('/provider/register', function ($request, $response) 
            use ($twig) {

            $provinceService = $this->get(ProvinceServicePort::class);
            $provinces = $provinceService->getProvinces();

            $html = $twig->render('pages/provider/pro_register.html.twig', [
                'provinces' => $provinces
            ]);

            $response->getBody()->write($html);
            return $response;
        })->add(new AuthMiddleware());


         /** ---------------------------
         * GET /provider/register-form
         * --------------------------- */
        $app->get('/provider/register-form-detail', function ($request, $response) 
            use ($twig) { 
            
            $provinceService = $this->get(ProvinceServicePort::class);
            $providerService = $this->get(ProviderServicePort::class);
            $registeredInformation = $providerService->getRegisterForm();
            $provinces = $provinceService->getProvinces();

            $html = $twig->render('pages/provider/register.form.detail.html.twig', [
                'information' => $registeredInformation,
                'provinces' => $provinces
            ]);

            $response->getBody()->write($html);
            return $response;
        })->add(new AuthMiddleware());


         /** ---------------------------
         * GET /provider/register-successfully
         * --------------------------- */        
        $app->get('/provider/register-successfully', function ($request, $response) 
            use ($twig) { 
            
            $html = $twig->render('pages/provider/pro_success.html.twig', [
            ]);

            $response->getBody()->write($html);
            return $response;
        });


        /** ---------------------------
         * GET /provider/register-failed
         * --------------------------- */        
        $app->get('/provider/register-failed', function ($request, $response) 
            use ($twig) { 
            
            $html = $twig->render('pages/provider/pro_failed.html.twig', [
            ]);

            $response->getBody()->write($html);
            return $response;
        });

         /** ---------------------------
         * GET /provider/utilities
         * --------------------------- */        
        $app->get('/provider/utilities', function ($request, $response) 
        { 
            $providerService = $this->get(ProviderServicePort::class);
            $utilities = $providerService->getUtilities();
            $payload = [
                'status' => 'success',
                'data'   => $utilities,
            ];

            $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json');
        })->add(new AuthMiddleware());


        /** ---------------------------
         * POST /provider/register
         * --------------------------- */
        $app->post('/provider/register', function ($request, $response) 
         {

             $providerService = $this->get(ProviderServicePort::class);
             $paymentService  = $this->get(InformationPaymentServicePort::class);
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
                        'redirect' => '/provider/register-successfully',
                    ];
                } else {
                    $payload = [
                        'status'  => 'error',
                        'message' => 'Đăng ký doanh nghiệp thất bại',
                        'redirect' => '/provider/register-failed',
                    ];
                }

            } catch (\Exception $e) {
                $payload = [
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                    'redirect' => '/provider/register-failed',
                ];
            }

            $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json');
        })->add(new AuthMiddleware());


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
         * GET /provider/dashboard
         * --------------------------- */        
        $group->get('/dashboard', function ($request, $response) 
            use ($twig, $providerService) { 
            
            $profile = $providerService->getProfile();
            $bookings = $providerService->getBookings();
            $html = $twig->render('pages/provider/provider.html.twig', [
                'profile' => $profile, 'bookings' => $bookings 
            ]);

            $response->getBody()->write($html);
            return $response;
        });
        
        $group->get('/{providerId}/vehicles', function ($request, $response, $args) use ($providerService) {
            $providerId = $args['providerId'];    
            $vehicles = $providerService->getVehiclesByProviderId($providerId);

            $response->getBody()->write(json_encode($vehicles));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        });

        $group->get('/{providerId}/{type}/drivers', function ($request, $response, $args) use ($providerService) {
            $providerId = $args['providerId'];
            $type = $args['type'];    
            $drivers = $providerService->getDriversByProviderId( $providerId, $type);

            $response->getBody()->write(json_encode($drivers));

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


    })
    ->add(new AuthMiddleware())
    ->add(new AuthorizationMiddleware(2));

};
