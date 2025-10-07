<?php
use Slim\App;
use  App\Application\Port\Inbound\ProvinceServicePort;


return function(App $app, $twig) {

    $app->get('/province/create', function ($request, $response, $args) use ($twig) {
        $response->getBody()->write($twig->render('pages/location/create-location.html.twig'));
        return $response;
    });

    $app->post('/province/create', function ($request, $response, $args) use ($twig) {

       $service = $this->get(ProvinceServicePort::class); 

       $body = $request->getParsedBody();        
       $uploadedFiles = $request->getUploadedFiles(); 

        $province = $service->createProvince($body, $uploadedFiles);

        $response->getBody()->write(json_encode([
            'status' => 'success',
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    });

};
