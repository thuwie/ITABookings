<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Inbound\InformationPaymentServicePort;
use App\Middleware\AuthMiddleware;

return function (App $app, $twig) {

    $app->group('/driver', function (RouteCollectorProxy $group) use ($twig) {

        // GET: đăng ký
        $group->get('/register', function ($request, $response, $args) use ($twig) {
            $service = $this->get(ProvinceServicePort::class);

        // Lấy danh sách provinces
            $provinces = $service->getProvinces();
            $html = $twig->render('pages/driver/dri_register.html.twig',
            ['provinces' => $provinces]);

            $response->getBody()->write($html);
            return $response;
        });

        // POST: xử lý đăng ký
    $group->post('/register', function ($request, $response) {
        try {
            $body = $request->getParsedBody();

            $providerInfo = json_decode($body['provider-information'], true);
            $paymentInfo  = json_decode($body['payment-information'], true);

            // files
            $files = $request->getUploadedFiles();
            $logo = $files['logo'] ?? null;
            $qr   = $files['qr'] ?? null;

            $serviceProvider = $this->get(ProviderServicePort::class);
            $serviceInformationPayment = $this->get(InformationPaymentServicePort::class);
            
            $resultPro = $serviceProvider->save($providerInfo, $logo);

            if ($resultPro) {
                $resultInforPayment = $serviceInformationPayment->save($paymentInfo, $qr);

                $payload = [
                    'status' => $resultInforPayment ? 'success' : 'error',
                    'message' => $resultInforPayment 
                        ? 'Đăng ký doanh nghiệp thành công' 
                        : 'Đăng ký doanh nghiệp thất bại',
                ];
            } else {
                $payload = [
                    'status' => 'error',
                    'message' => 'Đăng ký doanh nghiệp thất bại',
                ];
            }

        } catch (\Exception $e) {
            $payload = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    });


    })->add(new AuthMiddleware());

};
