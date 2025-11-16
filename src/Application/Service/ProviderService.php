<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Domain\Entity\Provider;

class ProviderService implements ProviderServicePort {
    private ProviderRepositoryPort  $providerRepositoryPort;
    private SessionManagerInterfacePort $sessionManager;


    public function __construct (
        ProviderRepositoryPort  $providerRepositoryPort,
        SessionManagerInterfacePort $sessionManager
    ) 
    {
        $this->providerRepositoryPort = $providerRepositoryPort;
        $this->sessionManager = $sessionManager;
    }

    public function save($provider, $logo): bool { 
        $name = $provider['name'];
        $email = $provider['email'];
        $phoneNumber = $provider['phone_number'];
        $address = $provider['address'];
        $province = (int) $provider['province_id'];
        $description = $provider['description'];
        $userSession = $this->sessionManager->get('user');
        $userId = $userSession['id'];
        $newProvider = new Provider(0, $userId, $name, '', $description, $email, $phoneNumber, $address, $province);

        $newProvider = $this->providerRepositoryPort->save($newProvider);

        if(!$newProvider) {
             throw new \Exception('Failed to register provider information');
        };

        $uploadedLogoUrl = $this->providerRepositoryPort->saveLogo($logo, $newProvider->getName());

        if(!$uploadedLogoUrl) {
             throw new \Exception('Failed to save logo');
        }

        $result = $this->providerRepositoryPort->savePathLogo($uploadedLogoUrl, $newProvider);

        return $result ? true : false;
    }
   
}
