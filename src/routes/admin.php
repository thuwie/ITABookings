<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Inbound\InformationPaymentServicePort;
use App\Application\Port\Inbound\UserServicePort;
use App\Application\Port\Inbound\AdminServicePort;
use App\Middleware\AuthMiddleware;

return function (App $app, $twig) {

    $app->group('/admin', function (RouteCollectorProxy $group) use ($twig) {

        /** -----------------------------------------
         *  Resolve dependencies ONCE per route group
         * ----------------------------------------- */
        $container = $group->getContainer();

        $provinceService = $container->get(ProvinceServicePort::class);
        $providerService = $container->get(ProviderServicePort::class);
        $paymentService  = $container->get(InformationPaymentServicePort::class);
        $userServices  = $container->get(UserServicePort::class);
        $adminServices  = $container->get(AdminServicePort::class);




        /** ---------------------------
         * GET /admin/dashboard
         * --------------------------- */
        $group->get('/dashboard', function ($request, $response) 
            use ($twig) {
            $html = $twig->render('pages/admin/dashboard.html.twig', []);
            $response->getBody()->write($html);
            return $response;
        });

         /** ---------------------------
         * API GET /admin/unverifiedProviders
         * --------------------------- */
        $group->get('/unverified-providers', function ($request, $response) use ($providerService, $userServices) {
            $unverifiedProviders = $providerService->getProviders(false);
            $ids = array_map(fn($provider) => $provider->getUserId(), $unverifiedProviders);
            $representatives = $userServices->getUsersById($ids);
            $payload = [
                'status' => 'success',
                 'data' => ['providers' => array_map(fn($p) => $p->toArray(), $unverifiedProviders), 'representatives' =>$representatives],
            ];

            $response->getBody()->write(json_encode($payload));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(200);
        });

         /** ---------------------------
         * API POST /admin/providers/{id}
         * --------------------------- */
      $group->patch('/providers/{id}', function ($request, $response, $args) use ($adminServices) {
        $id = (int) $args['id'];

        try {
            $result = $adminServices->approveProvider($id);

            if ($result) {
                $payload = ['status' => 'success'];
                $statusCode = 200;
            } else {
                $payload = ['status' => 'fail', 'message' => 'Duyệt thất bại'];
                $statusCode = 404;
            }
        } catch (\Exception $e) {
            $payload = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            $statusCode = 500;
        }

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($statusCode);
    });



    })->add(new AuthMiddleware());

};
