<?php
use Slim\App;
use  App\Application\Port\Inbound\ProvinceServicePort;


return function(App $app, $twig) {

    $app->get('/province/create', function ($request, $response, $args) use ($twig) {
        $response->getBody()->write($twig->render('pages/location/create-location.html.twig'));
        return $response;
    });

    $app->get('/province/detail', function ($request, $response, $args) use ($twig) {
        $response->getBody()->write($twig->render('pages/location/province-detail.html.twig'));
        return $response;
    });

    $app->post('/province/create', function ($request, $response, $args) use ($twig) {

       $service = $this->get(ProvinceServicePort::class); 

       $body = $request->getParsedBody();        
       $uploadedFiles = $request->getUploadedFiles(); 

        $imgs = $uploadedFiles['images'] ?? [];
        $result = $service->createProvince($body, $imgs);

        // Trả về JSON đúng với dữ liệu service trả
    
         $response->getBody()->write(json_encode($result)); 
         
        // Đặt header Content-Type cho chuẩn REST
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/provinces', function ($request, $response, $args) use ($twig) {

        $service = $this->get(ProvinceServicePort::class); 

        //  Lấy dữ liệu
        $provinces = $service->getProvinces();

        // Render Twig, truyền dữ liệu
        $response->getBody()->write($twig->render(
            'pages/travel_spot/create.travel.spot.html.twig',
            [
                'provinces' => $provinces,
            ]
        ));

        return $response;
    });

};
