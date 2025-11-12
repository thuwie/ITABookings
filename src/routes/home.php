<?php
use Slim\App;
use  App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\TravelSpotPort;
use App\Application\Port\Inbound\FoodCourtServicePort;
return function(App $app, $twig) {
    $app->get('/', function ($request, $response, $args) use ($twig) {
    $service = $this->get(ProvinceServicePort::class); 
    $serviceTravelSpot = $this->get(TravelSpotPort::class); 
    $serviceFoodCourt = $this->get(FoodCourtServicePort::class); 

    $provinces = $service->getProvincesWithImages();
    $travelSpots = $serviceTravelSpot->getTravelSpotsWithImages();
    $foodCourts = $serviceFoodCourt->getFoodCourtsWithImages();

    $provinces = array_slice($provinces, 0, 4); 
    $travelSpots = array_slice($travelSpots, 0, 4); 
    $foodCourts = array_slice($foodCourts, 0, 4);

    $html = $twig->render('pages/home.html.twig', [
        'provinces' => $provinces,
        'travelSpots' =>  $travelSpots,
        'foodCourts' => $foodCourts,
    ]);

    $response->getBody()->write($html);
    return $response;
});

// Form đăng ký
    $app->get('/register', function ($request, $response, $args) use ($twig) {
        
        $service = $this->get(ProvinceServicePort::class); 

        //  Lấy dữ liệu
        $provinces = $service->getProvinces();

        $html = $twig->render('pages/auth/register.html.twig', [
            'provinces' => $provinces,
        ]);
        $response->getBody()->write($html);
        return $response;
    });

    // Form dangnhap
    $app->get('/login', function ($request, $response, $args) use ($twig) {
        $html = $twig->render('pages/auth/login.html.twig', [
            
        ]);
        $response->getBody()->write($html);
        return $response;
    });

    // Xử lý form POST đăng ký
    $app->post('/login', function ($request, $response, $args) {
        $data = $request->getParsedBody();
        // TODO: lưu dữ liệu user vào DB

        $response->getBody()->write(json_encode(['success' => true]));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
