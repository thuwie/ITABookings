<?php
use Slim\App;

use App\Application\Port\Inbound\LoginUserUseCasePort;
return function(App $app, $twig) {

  $app->post('/login', function($request, $response, $args) use ($twig) {
    
    $rawBody = $request->getBody()->getContents();
    // Decode JSON
    $data = json_decode($rawBody, true);

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';


    try {
        $user = $this->get(LoginUserUseCasePort::class)->login($email, $password);

        // Trả về JSON khi login thành công
        $result = [
            'status' => 'success',
            'message' => 'Đăng nhập thành công',
            'redirect' => '/'  // JS sẽ xử lý chuyển trang
        ];

    } catch (\Exception $e) {
        $result = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
    });
};
