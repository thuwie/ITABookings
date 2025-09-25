<?php
use Slim\App;

return function(App $app, $twig) {
    $app->get('/', function ($request, $response, $args) use ($twig) {
        $response->getBody()->write($twig->render('pages/home.html.twig'));
        return $response;
    });
};
