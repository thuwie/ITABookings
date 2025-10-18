<?php
use Slim\App;
use App\Application\Port\Inbound\TravelSpotPort;
use  App\Application\Port\Inbound\ProvinceServicePort;

return function(App $app, $twig) {

    $app->get('/food-court/create', function ($request, $response, $args) use ($twig) {
         // Lấy service
    $service = $this->get(ProvinceServicePort::class);

    // Lấy danh sách provinces
    $provinces = $service->getProvinces();

    // Render và truyền dữ liệu vào Twig
    $response->getBody()->write(
        $twig->render('pages/food_court/create.food.court.html.twig', [
            'provinces' => $provinces
        ])
    );

    return $response;
    });

    //  $app->post('/travel-spot/create', function ($request, $response, $args) use ($twig) {

    //    $service = $this->get(TravelSpotPort::class); 

    //    $body = $request->getParsedBody();        
    //    $uploadedFiles = $request->getUploadedFiles(); 

    //     $imgs = $uploadedFiles['images'] ?? [];
    //     $result = $service->createTravelSpot($body, $imgs);

    //     // Trả về JSON đúng với dữ liệu service trả
    
    //      $response->getBody()->write(json_encode($result)); 
         
    //     // Đặt header Content-Type cho chuẩn REST
    //     return $response->withHeader('Content-Type', 'application/json');
    // });
};
