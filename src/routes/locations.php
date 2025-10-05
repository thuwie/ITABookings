<?php
use Slim\App;
use App\Application\Service\LocationService;


return function(App $app, $twig) {
    $app->get('/locations', function ($request, $response, $args) use ($twig) {

        $service = $this->get(LocationService::class); 

        //  Lấy dữ liệu
        $provinces = $service->getProvincesWithWards();

        // Render Twig, truyền dữ liệu
        $response->getBody()->write($twig->render(
            'pages/location/locations.html.twig',
            [
                'provinces' => $provinces,
            ]
        ));

        return $response;
    });

    $app->get('/location-create', function ($request, $response, $args) use ($twig) {
        $response->getBody()->write($twig->render('pages/location/create-location.html.twig'));
        return $response;
    });

    $app->post('/locations/create-province', function ($request, $response, $args) use ($twig) {

       $service = $this->get(LocationService::class); 

       $body = $request->getParsedBody();        
       $uploadedFiles = $request->getUploadedFiles(); 

        $province = $service->createProvince($body, $uploadedFiles);

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'province_id' => $province->getId()
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    });

};
