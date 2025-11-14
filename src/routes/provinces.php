<?php
use Slim\App;
use  App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\TravelSpotPort;
use App\Application\Port\Inbound\FoodCourtServicePort;
use App\Helper\FileHelper;

return function(App $app, $twig) {

    $app->get('/province/create', function ($request, $response, $args) use ($twig) {
        $response->getBody()->write($twig->render('pages/province/create-province.html.twig'));
        return $response;
    });

    $app->get('/province/{id}', function ($request, $response, $args) use ($twig) {
        $id = $args['id'];

        $provinces = $this->get(ProvinceServicePort::class)->getProvinces();
        $service = $this->get(ProvinceServicePort::class);
        $serviceTravelSpot = $this->get(TravelSpotPort::class);
        $serviceFoodCourt = $this->get(FoodCourtServicePort::class);


        // Lấy province kèm images
        $province = $service->getProvinceByIdWithImages($id);

        //Lấy travel spot kèm images
        $travelSpots =  $serviceTravelSpot->getTravelSpotsWithImagesByProvinceId($id);

        //Lấy Food court kèm images
        $foodCourts = $serviceFoodCourt->getFoodCourtsWithImagesByProvinceId($id);

        $html = $twig->render('pages/province/province-detail.html.twig', [
            'province' => $province,
            'travelSpots' =>$travelSpots,
            'foodCourts' => $foodCourts,
            'provinces' => $provinces
        ]);

        $response->getBody()->write($html);
        return $response;
    });


    $app->get('/provinces', function ($request, $response, $args) use ($twig) {

        $service = $this->get(ProvinceServicePort::class); 

        //  Lấy dữ liệu
        $provinces = $service->getProvincesWithImages();

        // Render Twig, truyền dữ liệu
        $response->getBody()->write($twig->render(
            'pages/province/get.all.provinces.html.twig',
            [
                'provinces' => $provinces,
            ]
        ));

        return $response;
    });

    $app->get('/provinces-with-travel-spots', function ($request, $response, $args) use ($twig) {

        $service = $this->get(ProvinceServicePort::class); 

        //  Lấy dữ liệu
        $data = $service->getProvincesWithTravelSports();

        // Render Twig, truyền dữ liệu
        $response->getBody()->write($twig->render(
            'pages/province/provinces-with-travel-spots.html.twig',
            [
                'provincesWithTravelSpots' => $data,
            ]
        ));

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

   $app->get('/food-courts-belong-provinces', function ($request, $response, $args) use ($twig) {

        $service = $this->get(ProvinceServicePort::class); 

        //  Lấy dữ liệu
        $data = $service->getFoodCourtsBelongTpProvince();
        foreach ($data as &$item) {
            $foodCourts = $item['foodCourts'] ?? [];

            $item['foodCourts'] = array_map(function ($foodCourt) {
                // Convert entity to array
                $foodCourtArray = $foodCourt->toArray();

                $foodCourtArray['price_from_formatted'] = FileHelper::formatCurrency($foodCourtArray['price_from']);
                $foodCourtArray['price_to_formatted']   = FileHelper::formatCurrency($foodCourtArray['price_to']);
                $foodCourtArray['open_close'] = FileHelper::formatTimeRange($foodCourtArray['open_time'], $foodCourtArray['close_time']);

                return $foodCourtArray;
            }, $foodCourts);
        }
        // Render Twig, truyền dữ liệu
        $response->getBody()->write($twig->render(
            'pages/province/provinces.with.food.courts.html.twig',
            [
                'provincesWithFoodCourts' => $data,
            ]
        ));

        return $response;
    });

};
