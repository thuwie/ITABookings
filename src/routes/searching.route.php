<?php
use Slim\App;
use  App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\TravelSpotPort;
use App\Application\Port\Inbound\FoodCourtServicePort;
return function(App $app, $twig) {
    $app->get('/searching-routes', function ($request, $response, $args) use ($twig) {
    $service = $this->get(ProvinceServicePort::class); 

    $provinces = $service->getProvinces();

    $html = $twig->render('pages/searching.html.twig', [
        'provinces' => $provinces,
    ]);

    $response->getBody()->write($html);
    return $response;
});

};
