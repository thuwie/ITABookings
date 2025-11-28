<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Inbound\InformationPaymentServicePort;
use App\Application\Port\Inbound\UserServicePort;
use App\Application\Port\Inbound\DriverServicePort;
use App\Middleware\AuthorizationMiddleware;
use App\Middleware\AuthMiddleware;

return function (App $app, $twig) {

    $app->group('/driver', function (RouteCollectorProxy $group) use ($twig) {

        // Load services ONCE here
        $container = $group->getContainer();

        $provinceServices  = $container->get(ProvinceServicePort::class);
        $providerService  = $container->get(ProviderServicePort::class);
        $paymentService   = $container->get(InformationPaymentServicePort::class);
        $userServices = $container->get(UserServicePort::class);
        $driverServices  =  $container->get(DriverServicePort::class);

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

         // GET
        $group->get('/register-successfully', function ($request, $response) use ($twig, $userServices, $providerService, $provinceServices) {
        
            $html = $twig->render('pages/driver/dri_success.html.twig');

            $response->getBody()->write($html);
            return $response;
        });

         // GET
        $group->get('/register-failed', function ($request, $response) use ($twig, $userServices, $providerService, $provinceServices) {

            $html = $twig->render('pages/driver/dri_failed.html.twig');

            $response->getBody()->write($html);
            return $response;
        });

        // POST
        $group->post('/register', function ($request, $response) use ($paymentService, $driverServices) {

            try {
                $body = $request->getParsedBody();

                $driverInfo = json_decode($body['driver-information'], true);
                $paymentInfo  = json_decode($body['payment-information'], true);

                $files = $request->getUploadedFiles();
                $qr   = $files['qr_image'] ?? null;

                $driverResult = $driverServices->save($driverInfo);

                if ($driverResult) {
                    $paymentResult = $paymentService->save($paymentInfo, $qr);

                    $payload = [
                        'status'  => $paymentResult  ? 'success' : 'error',
                        'message' => $paymentResult  
                            ? 'Đăng ký tài xế thành công'
                            : 'Đăng ký tài xế thất bại'
                        , 'redirect' => '/driver/register-successfully'
                    ];
                } else {
                    $payload = [
                        'status' => 'error',
                        'message' => 'Đăng ký tài xế thất bại',
                        'redirect' => '/driver/register-failed'
                    ];
                }

            } catch (\Exception $e) {
                $payload = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'redirect' => '/driver/register-failed'
                ];
            }

            $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json');
        });

    })->add(new AuthMiddleware())
    ->add(new AuthorizationMiddleware(3));

};

