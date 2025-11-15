<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\InformationServicePort;
use App\Application\Port\Outbound\InformationPaymentPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Application\Port\Outbound\UploadImageRepositoryPort;
use App\Domain\Entity\InformationPayment;
use Carbon\Exceptions\Exception;

class InformationPaymentService implements InformationServicePort {
    private InformationPaymentPort  $informationPayment;
    private SessionManagerInterfacePort $sessionManager;
    private UploadImageRepositoryPort $uploadImageRepositoryPort;

    public function __construct (
        InformationPaymentPort  $informationPayment,
        SessionManagerInterfacePort $sessionManager,
        UploadImageRepositoryPort $uploadImageRepositoryPort
    ) 
    {
        $this->informationPayment = $informationPayment;
        $this->sessionManager = $sessionManager;
        $this->uploadImageRepositoryPort = $uploadImageRepositoryPort;
    }

    public function save($informationPaymentInput, $qr): bool { 
        $bankName = $informationPaymentInput['bank_name'];
        $accountNumber = $informationPaymentInput['account_number'];
        $fullName = $informationPaymentInput['full_name_account'];
        $userSession = $this->sessionManager->get('user');
        $userId = $userSession['id'];
        $userName = $userSession['first_name'] + $userSession['last_name'];

        $newInformationPayment = new InformationPayment(0, $userId, $fullName, $accountNumber, $bankName);
        $addedInformationPayment = $this->informationPayment->save($newInformationPayment);

        if (!$addedInformationPayment) {
            throw new \RuntimeException('Failed to save information payment.');
        }
        
        $uploadedOrUrl = $this->uploadImageRepositoryPort->saveOne($qr, 'QRs', $userName);

        
        if (!$uploadedOrUrl) {
            throw new \RuntimeException('Failed to save information payment.');
        }

        $assignQr =  $newInformationPayment->setQrCode($uploadedOrUrl);

        if($assignQr) {
             $updatedInformationPayment = $this->informationPayment->update($assignQr);
             return $updatedInformationPayment ? true : false;
        }
       
        return false;;
    }
   
}
