<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Inbound\InformationPaymentServicePort;
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

            $html = $twig->render('pages/provider/register.form.html.twig', [
                'information' => $registeredInformation,
                'provinces' => $provinces
            ]);

            $response->getBody()->write($html);
            return $response;
        });


        /** ---------------------------
         * GET /provider/{id}
         * --------------------------- */
        $group->get('/{id}', function ($request, $response) 
            use ( $providerService) {

            $id = $request->getAttribute('id');

            $provider = $providerService->getProviderById($id);

            $response->getBody()->write(json_encode([
                'success' => true,
                'provider' => $provider,
            ]));

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

    })->add(new AuthMiddleware());

};
