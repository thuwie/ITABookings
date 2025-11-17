<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Inbound\InformationPaymentServicePort;
use App\Application\Port\Inbound\UserServicePort;


use App\Middleware\AuthMiddleware;

return function (App $app, $twig) {

    $app->group('/driver', function (RouteCollectorProxy $group) use ($twig) {

        // Load services ONCE here
        $container = $group->getContainer();

        $provinceServices  = $container->get(ProvinceServicePort::class);
        $providerService  = $container->get(ProviderServicePort::class);
        $paymentService   = $container->get(InformationPaymentServicePort::class);
        $userServices = $container->get(UserServicePort::class);

        // GET
        $group->get('/register', function ($request, $response) use ($twig, $userServices, $providerService, $provinceServices) {
            $user = $userServices->getUserInformation();
            $providers = $providerService->getProviders(null);
            $provinces = $provinceServices->getProvinces();

            $html = $twig->render('pages/driver/dri_register.html.twig', 
            ['user'=> $user,'providers' =>  $providers, 'provinces' => $provinces
            ]);

            $response->getBody()->write($html);
            return $response;
        });

        // POST
        $group->post('/register', function ($request, $response) use ($providerService, $paymentService) {

            try {
                $body = $request->getParsedBody();

                $providerInfo = json_decode($body['provider-information'], true);
                $paymentInfo  = json_decode($body['payment-information'], true);

                $files = $request->getUploadedFiles();
                $logo = $files['logo'] ?? null;
                $qr   = $files['qr'] ?? null;

                $resultPro = $providerService->save($providerInfo, $logo);

                if ($resultPro) {
                    $resultPayment = $paymentService->save($paymentInfo, $qr);

                    $payload = [
                        'status'  => $resultPayment ? 'success' : 'error',
                        'message' => $resultPayment 
                            ? 'Đăng ký doanh nghiệp thành công'
                            : 'Đăng ký doanh nghiệp thất bại'
                    ];
                } else {
                    $payload = [
                        'status' => 'error',
                        'message' => 'Đăng ký doanh nghiệp thất bại'
                    ];
                }

            } catch (\Exception $e) {
                $payload = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }

            $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json');
        });

    })->add(new AuthMiddleware());

};

