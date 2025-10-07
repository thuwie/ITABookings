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

    //Sửa lại nó sẽ call đến province service chứ không phải location service
    

};
