<?php
namespace App\Adapter\Inbound;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Application\Port\Inbound\UserServicePort;

class UserController {
    private UserServicePort $service;

    public function __construct(UserServicePort $service) {
        $this->service = $service;
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $data = json_decode($request->getBody()->getContents(), true);

        $firstName    = $data['first_name'] ?? '';
        $lastName     = $data['last_name'] ?? '';
        $password     = $data['password'] ?? '';
        $email        = $data['email'] ?? '';
        $phoneNumber  = $data['phone_number'] ?? null;
        $gender       = $data['gender'] ?? 'male';
        $dateOfBirth  = $data['date_of_birth'] ?? null;
        $cccd         = $data['cccd'] ?? null;
        $address      = $data['address'] ?? null;
        $provinceCode = $data['province'] ?? null;
        $roleId       = 4; // Mặc định 'user'

        try {
            // Nếu cần, convert provinceCode thành province_id (bạn có thể gọi repository)
            $provinceId = null;
            if ($provinceCode) {
                // Giả sử service có hàm getProvinceIdByCode()
                $provinceId = $provinceCode;
            }
            
            $result = $this->service->createUser(
                $firstName,
                $lastName,
                $password,       // Service sẽ hash password
                $email,
                $phoneNumber,
                $gender,
                $dateOfBirth,
                $cccd,
                $address,
                $provinceId,
                $roleId
            );

            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(201); // Created
        } catch (\DomainException $e) {
            $error = ['error' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(400); // Bad Request
        }
    }
}
