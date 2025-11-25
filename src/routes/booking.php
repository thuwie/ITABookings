<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Inbound\UserServicePort;
use App\Application\Port\Inbound\InformationPaymentServicePort;
use App\Application\Port\Inbound\BookingServicePort;

use App\Middleware\AuthMiddleware;

return function (App $app, $twig) {

    $app->group('/booking', function (RouteCollectorProxy $group) use ($twig) {

        /** -----------------------------------------
         *  Resolve dependencies ONCE per route group
         * ----------------------------------------- */
        $container = $group->getContainer();

        $providerService = $container->get(ProviderServicePort::class);
        $paymentService  = $container->get(InformationPaymentServicePort::class);
        $userServices = $container->get(UserServicePort::class);
        $userServices = $container->get(UserServicePort::class);
        $bookingServices = $container->get(BookingServicePort::class);

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

        /** ---------------------------
         * GET /booking/register-successfully
         * --------------------------- */
        $group->get('/register-successfully', function ($request, $response) 
            use ($twig) {

           $html = $twig->render('pages/booking/booking.successfully.html.twig');

            $response->getBody()->write($html);
            return $response;

        });

        /** ---------------------------
         * GET /booking/register-failed
         * --------------------------- */
        $group->get('/register-failed', function ($request, $response) 
            use ($twig) {
           $html = $twig->render('pages/booking/booking.failed.html.twig');

            $response->getBody()->write($html);
            return $response;

        });

        /** ---------------------------
         * POST /booking/{id}
         * --------------------------- */
       $group->post('/{id}', function ($request, $response, $args) use ($bookingServices) {
        $id = $args['id']; 

        $raw = (string)$request->getBody();
        $body = json_decode($raw, true);

        if (!$body) {
            return $response->withJson([
                'status' => 'error',
                'message' => 'Invalid JSON',
                'redirect' => '/booking/register-failed'
            ]);
        }

        try {
            $result =  $bookingServices->save($body, $id);

            $payload = [
                'status'  => $result ? 'success' : 'error',
                'message' => $result ? 'Đặt xe thành công' : 'Đặt xe thất bại',
                'redirect' => $result ? '/booking/register-successfully' : '/booking/register-failed'
            ];

        } catch (\Exception $e) {
            $payload = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'redirect' => '/booking/register-failed'
            ];
        }

        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    });

    })->add(new AuthMiddleware());

};
