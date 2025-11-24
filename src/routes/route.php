<?php
use Slim\App;
use  App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\RouteServicePort;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Middleware\AuthMiddleware;

return function(App $app, $twig) {
    $app->get('/searching-routes', function ($request, $response, $args) use ($twig) {
        $service = $this->get(ProvinceServicePort::class); 
        $providerServices = $this->get(ProviderServicePort::class);

        $provinces = $service->getProvinces();
        $providers = $providerServices->getProviders(true);
        $seatCounting =   $providerServices->getSeatCounting();


        $html = $twig->render('pages/routes/searching.html.twig', [
            'provinces' => $provinces,
            'providers' =>$providers,
            'seat_counting' => $seatCounting
        ]);

        $response->getBody()->write($html);
        return $response;
    })->add(new AuthMiddleware());

    $app->get('/routes', function ($request, $response, $args) use ($twig) {

        $params = $request->getQueryParams();
        $from = $params['from'] ?? null;
        $to = $params['to'] ?? null;
        $seat_counting = $params['seat_counting'] ?? null;
        $provider = $params['provider'] ?? null;

        $routeServices = $this->get(RouteServicePort::class);
        $result = null;

        if($from && $to) {
            $data['from'] = $from;
            $data['to'] = $to;
            $data['seat_counting'] = $seat_counting;
            $data['provider'] = $provider;
            $result =  $routeServices->findVehiclesByRoute($data);
        }

       $payload = json_encode(['data' => $result]);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    })->add(new AuthMiddleware());

    // $app->get('/routes', function ($request, $response, $args) use ($twig) {

    //     $params = $request->getQueryParams();
    //     $from = $params['from'] ?? null;
    //     $to = $params['to'] ?? null;

    //     $routes = $this->get(RouteServicePort::class)->findRoutes($from, $to);

    //     $response->getBody()->write(json_encode($routes));
    //     return $response->withHeader('Content-Type', 'application/json');
    // });

    $app->get('/create-routes', function ($request, $response, $args) use ($twig) {
        $service = $this->get(ProvinceServicePort::class); 

        $provinces = $service->getProvinces();

        $html = $twig->render('pages/routes/create.route.html.twig', [
        ]);

        $response->getBody()->write($html);
        return $response;
    })->add(new AuthMiddleware());

    $app->post('/route', function ($request, $response, $args) use ($twig) {

       $service = $this->get(RouteServicePort::class); 

       $body = $request->getParsedBody();        

        $result = $service->createRoute($body);

        // Trả về JSON đúng với dữ liệu service trả
    
         $response->getBody()->write(json_encode($result)); 
         
        // Đặt header Content-Type cho chuẩn REST
        return $response->withHeader('Content-Type', 'application/json');
    })->add(new AuthMiddleware());

};
