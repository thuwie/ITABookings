<?php
use Slim\App;
use App\Application\Port\Inbound\TravelSpotPort;
use  App\Application\Port\Inbound\ProvinceServicePort;
use App\Middleware\AuthMiddleware;
use App\Middleware\AuthorizationMiddleware;
use App\Helper\FileHelper;
return function(App $app, $twig) {

    $app->get('/travel-spot/create', function ($request, $response, $args) use ($twig) {
         // Lấy service
        $service = $this->get(ProvinceServicePort::class);

        // Lấy danh sách provinces
        $provinces = $service->getProvinces();

        // Render và truyền dữ liệu vào Twig
        $response->getBody()->write(
            $twig->render('pages/travel_spot/create.travel.spot.html.twig', [
                'provinces' => $provinces
            ])
        );

        return $response;
        })->add(new AuthMiddleware())->add(new AuthorizationMiddleware(1));

        $app->get('/travel-spot/{id}', function ($request, $response, $args) use ($twig) {
        $id = $args['id'];

        $serviceTravelSpot = $this->get(TravelSpotPort::class);
        $provinces = $this->get(ProvinceServicePort::class)->getProvinces();
        $travelSpot = $serviceTravelSpot->getById($id);

        if ($travelSpot) {
            // Format giá
            $travelSpot['price_from_formatted'] = FileHelper::formatCurrency($travelSpot['price_from']);
            $travelSpot['price_to_formatted']   = FileHelper::formatCurrency($travelSpot['price_to']);

            // Format giờ sang dạng 12h có AM/PM
            $travelSpot['open_close'] = FileHelper::formatTimeRange($travelSpot['open_time'], $travelSpot['close_time']);
        }

        $anotherTravelSpotsWithSameIdProvince = $serviceTravelSpot->getTravelSpotsWithImagesByProvinceId($travelSpot['province_id']);
        $anotherTravelSpotsWithSameIdProvince = array_filter(
                $anotherTravelSpotsWithSameIdProvince,
                fn($spot) => $spot->getId() !== (int)$id
        );
        $html = $twig->render('pages/travel_spot/travel-spot.detail.html.twig', [
            'anotherTravelSpotsWithSameIdProvince' => $anotherTravelSpotsWithSameIdProvince,
            'travelSpot' =>$travelSpot,
            'provinces' => $provinces,
        ]);

        $response->getBody()->write($html);
        return $response;
    });

     $app->post('/travel-spot/create', function ($request, $response, $args) use ($twig) {

       $service = $this->get(TravelSpotPort::class); 

       $body = $request->getParsedBody();        
       $uploadedFiles = $request->getUploadedFiles(); 

        $imgs = $uploadedFiles['images'] ?? [];
        $result = $service->createTravelSpot($body, $imgs);

        // Trả về JSON đúng với dữ liệu service trả
    
         $response->getBody()->write(json_encode($result)); 
         
        // Đặt header Content-Type cho chuẩn REST
        return $response->withHeader('Content-Type', 'application/json');
    })->add(new AuthMiddleware())
    ->add(new AuthorizationMiddleware(1));

     $app->get('/travel-spot/{id}/province', function ($request, $response, $args)  {
        $id = (int) $args['id'];
        $travelSpotServices = $this->get(TravelSpotPort::class);
        $result = $travelSpotServices->getProvinceByTravelSpotId($id);
        $payload = ['data' => $result];
        // Write JSON to response
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
