<?php
use Slim\App;
use  App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\RouteServicePort;
return function(App $app, $twig) {
    $app->get('/searching-routes', function ($request, $response, $args) use ($twig) {
        $service = $this->get(ProvinceServicePort::class); 

        $provinces = $service->getProvinces();

        $html = $twig->render('pages/routes/searching.html.twig', [
            'provinces' => $provinces,
        ]);

        $response->getBody()->write($html);
        return $response;
    });

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
