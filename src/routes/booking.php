<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Inbound\UserServicePort;
use App\Application\Port\Inbound\InformationPaymentServicePort;
use App\Application\Port\Inbound\AdminServicePort;
use App\Middleware\AuthMiddleware;

return function (App $app, $twig) {

    $app->group('/booking', function (RouteCollectorProxy $group) use ($twig) {

        /** -----------------------------------------
         *  Resolve dependencies ONCE per route group
         * ----------------------------------------- */
        $container = $group->getContainer();

        $provinceService = $container->get(ProvinceServicePort::class);
        $providerService = $container->get(ProviderServicePort::class);
        $paymentService  = $container->get(InformationPaymentServicePort::class);
        $adminServices  = $container->get(AdminServicePort::class);
        $userServices = $container->get(UserServicePort::class);


        
        /** ---------------------------
         * GET /booking/confirming
         * --------------------------- */
        $group->get('/confirming', function ($request, $response) 
            use ($twig, $userServices, $providerService) {
            
            $params = $request->getQueryParams();
            $userId     = (int) $params['user'] ?? null;
            $providerId = (int) $params['provider'] ?? null;
            $vehicleId  = (int) $params['vehicle']  ?? null;
            $route    = $params['route']    ?? null;
            $from     = $params['from']     ?? null;
            $to       = $params['to']       ?? null;
            $km       = $params['km']       ?? null;
            $price    = $params['price']    ?? null;

            $userInformation = $userServices->getUserById($userId);
            $providerWithVehicle = $providerService->getProviderWithVehicle($providerId, $vehicleId);
            $grouping = ['route' =>$route, 'from' => $from , 'to' => $to, 'km' => $km, 'price' => $price];

           $html = $twig->render('pages/booking/booking.html.twig', [
                'routeDetail' => $grouping,
                'user' => $userInformation,
                'providerWithVehicle' => $providerWithVehicle
            ]);

            $response->getBody()->write($html);
            return $response;

        });

    })->add(new AuthMiddleware());

};
