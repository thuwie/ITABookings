<?php
use Slim\App;
use App\Application\Port\Inbound\TravelSpotPort;
use  App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\FoodCourtServicePort;
use App\Helper\FileHelper;

return function(App $app, $twig) {

    $app->get('/food-court/create', function ($request, $response, $args) use ($twig) {
         // Lấy service
        $service = $this->get(ProvinceServicePort::class);
        $servicesTravelSpot = $this->get(TravelSpotPort::class);


        // Lấy danh sách provinces
        $provinces = $service->getProvinces();
        $travelSpots = $servicesTravelSpot->getTravelSpots();

        // Render và truyền dữ liệu vào Twig
        $response->getBody()->write(
            $twig->render('pages/food_court/create.food.court.html.twig', [
                'provinces' => $provinces,
                'travelSpots' =>$travelSpots,
            ]));

        return $response;
    });

    $app->post('/food-court/create', function ($request, $response, $args) use ($twig) {

        /** @var FoodCourtServicePort $service */
        $service = $this->get(FoodCourtServicePort::class);
        // Lấy dữ liệu từ form
        $body = $request->getParsedBody();

        // Ép kiểu và chuẩn hóa dữ liệu
        $data = [
            'name'          => $body['name'] ?? '',
            'description'   => $body['description'] ?? null,
            'address'       => $body['address'] ?? null,
            'province_id'   => isset($body['province_id']) && $body['province_id'] !== '' ? (int)$body['province_id'] : 0,
            'travel_spot_id'=> isset($body['travel_spot_id']) && $body['travel_spot_id'] !== '' ? (int)$body['travel_spot_id'] : 0,
            'open_time'     => $body['open_time'] ?? null,
            'close_time'    => $body['close_time'] ?? null,
            'price_from'    => isset($body['price_from']) ? (float)$body['price_from'] : null,
            'price_to'      => isset($body['price_to']) ? (float)$body['price_to'] : null,
        ];

        // Lấy file upload
        $uploadedFiles = $request->getUploadedFiles();
        $imgs = $uploadedFiles['images'] ?? [];

        // Gọi service tạo food court
        $result = $service->createFoodCourt($data, $imgs); // chú ý đổi tên hàm cho đúng

        // Trả về JSON
        $response->getBody()->write(json_encode($result));

        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/food-court/{id}', function ($request, $response, $args) use ($twig) {
        $id = $args['id'];

        $serviceFoodCourt = $this->get(FoodCourtServicePort::class);
        $provinces = $this->get(ProvinceServicePort::class)->getProvinces();

        $foodCourt = $serviceFoodCourt->getFoodCourtById($id);
            if ($foodCourt ) {
            // Format giá
            $foodCourt['price_from_formatted'] = FileHelper::formatCurrency($foodCourt['price_from']);
            $foodCourt['price_to_formatted']   = FileHelper::formatCurrency($foodCourt['price_to']);

            // Format giờ sang dạng 12h có AM/PM
            $foodCourt['open_close'] = FileHelper::formatTimeRange($foodCourt['open_time'], $foodCourt['close_time']);
        }


        $provinceId= $foodCourt['province_id'];
        $relatedFoodCourts = $serviceFoodCourt->getFoodCourtsWithImagesByProvinceId($provinceId);
        $relatedFoodCourts = array_filter(
                    $relatedFoodCourts ,
                    fn($spot) => $spot->getId() !== (int)$id
            );

        $html = $twig->render('pages/food_court/food-court-detail.html.twig', [
            'foodCourt' =>$foodCourt,
            'relatedFoodCourts' =>$relatedFoodCourts,
            'provinces' => $provinces 
        ]);

        $response->getBody()->write($html);
        return $response;
    });

    $app->get('/food-court/{id}/province', function ($request, $response, $args)  {
        $id = (int) $args['id'];
        $foodCourtServices = $this->get(FoodCourtServicePort::class);
        $result = $foodCourtServices->getProvinceByFoodCourtId($id);
        $payload = ['data' => $result];
        // Write JSON to response
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    });

    };
