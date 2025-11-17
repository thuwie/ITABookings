<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Application\Port\Outbound\InformationPaymentPort;
use App\Domain\Entity\Provider;

class ProviderService implements ProviderServicePort {
    private ProviderRepositoryPort  $providerRepositoryPort;
    private SessionManagerInterfacePort $sessionManager;
    private InformationPaymentPort $paymentInformation;


    public function __construct (
        ProviderRepositoryPort  $providerRepositoryPort,
        SessionManagerInterfacePort $sessionManager,
        InformationPaymentPort $paymentInformation
    ) 
    {
        $this->providerRepositoryPort = $providerRepositoryPort;
        $this->sessionManager = $sessionManager;
        $this->paymentInformation = $paymentInformation;
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

        if($result) {
            $userSession['temporary_role'] = "provider";
            $this->sessionManager->set('user', $userSession);
        }
        
        return $result ? true : false;
    }
   
    public function getRegisterForm(): array {
        $userSession = $this->sessionManager->get("user");
        $userId = (int) $userSession['id'];

        $formInformation = $this->providerRepositoryPort->findUnVerifiedAccountByUserId($userId);
        $paymentInformation = $this->paymentInformation->getPaymentInformationByUserId($userId);
        
        if(!$formInformation || !$paymentInformation) {
              throw new \Exception('Not found registered information !!!');
        }

        $data = [
            'registeredInformation' => $formInformation->toArray(),
            'paymentInformation' =>$paymentInformation->toArray()
        ];

        return $data;
    }
}
