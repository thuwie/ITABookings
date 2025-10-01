<?php
use Slim\App;
use App\Application\Service\LocationService;
use App\Adapter\Outbound\RedisCacheAdapter;
use App\Adapter\Outbound\LocationApiAdapter;

return function(App $app, $twig) {
    $app->get('/locations', function ($request, $response, $args) use ($twig) {

        // 1️⃣ Khởi tạo adapter + service
        $cache = new RedisCacheAdapter();
        $apiPort = new LocationApiAdapter(); // implement LocationApiPort
        $service = new LocationService($apiPort, $cache);

        // 2️⃣ Lấy dữ liệu
        $provinces = $service->listProvinces();
        // $districts = $service->listDistricts(1); // ví dụ provinceId = 1

        // 3️⃣ Render Twig, truyền dữ liệu
        $response->getBody()->write($twig->render(
            'pages/location/locations.html.twig',
            [
                'provinces' => $provinces,
            ]
        ));

        return $response;
    });
};
