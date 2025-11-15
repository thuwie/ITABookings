<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Application\Port\Inbound\ProvinceServicePort;
use App\Middleware\AuthMiddleware;

return function (App $app, $twig) {

    $app->group('/provider', function (RouteCollectorProxy $group) use ($twig) {

        // GET: đăng ký
        $group->get('/register', function ($request, $response, $args) use ($twig) {
            $service = $this->get(ProvinceServicePort::class);

        // Lấy danh sách provinces
            $provinces = $service->getProvinces();
            $html = $twig->render('pages/provider/pro_register.html.twig',
            ['provinces' => $provinces]);

            $response->getBody()->write($html);
            return $response;
        });

        // POST: xử lý đăng ký
        $group->post('/register', function ($request, $response, $args) {

            $rawBody = $request->getBody()->getContents();
            $data = json_decode($rawBody, true);

            try {
                // $service = $this->get(UserServicePort::class);
                // $result = $service->createUser($data);

                // if ($result['status'] === 'success') {
                //     $result['redirect'] = '/provider/login'; 
                // }

            } catch (\Exception $e) {
                $result = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }

            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        });

    })->add(new AuthMiddleware());

};
