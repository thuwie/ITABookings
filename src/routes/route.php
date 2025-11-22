<?php
use Slim\App;
use  App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\RouteServicePort;
return function(App $app, $twig) {
    $app->get('/searching-routes', function ($request, $response, $args) use ($twig) {

        $params = $request->getQueryParams();
        $from = $params['from'] ?? null;
        $to = $params['to'] ?? null;

        $service = $this->get(ProvinceServicePort::class); 

        $provinces = $service->getProvinces();
        $routeServices = $this->get(RouteServicePort::class);
        $data['from'] = $from;
        $data['to'] = $to;

        $result =   $routeServices->findVehiclesByRoute($data);


        $html = $twig->render('pages/routes/searching.html.twig', [
            'provinces' => $provinces,
            'from' => $from,
            'to' => $to,
        ]);

        $response->getBody()->write($html);
        return $response;
    });

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
    });

    $app->post('/route', function ($request, $response, $args) use ($twig) {

       $service = $this->get(RouteServicePort::class); 

       $body = $request->getParsedBody();        

        $result = $service->createRoute($body);

        // Trả về JSON đúng với dữ liệu service trả
    
         $response->getBody()->write(json_encode($result)); 
         
        // Đặt header Content-Type cho chuẩn REST
        return $response->withHeader('Content-Type', 'application/json');
    });

};
