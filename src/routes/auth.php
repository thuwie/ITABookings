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
    $redirectUrl = $_SESSION['redirect_after_login'] ?? '/';
    unset($_SESSION['redirect_after_login']);


    try {
        $user = $this->get(LoginUserUseCasePort::class)->login($email, $password);

        $twig->addGlobal('authUser', $user);
        // Trả về JSON khi login thành công
        $result = [
            'status' => 'success',
            'message' => 'Đăng nhập thành công',
            'redirect' =>  $redirectUrl
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

    $app->get('/logout', function ($request, $response) {

        // Start session nếu chưa chạy
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Xóa toàn bộ session
        session_unset();
        session_destroy();

        // Tạo session mới để tránh lỗi
        session_start();

        // Redirect về trang chủ
        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    });
};
