<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\DriverServicePort;
use App\Application\Port\Outbound\DriverRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Domain\Entity\Driver;

class DriverService implements DriverServicePort {
    private DriverRepositoryPort  $driverRepositoryPort;
    private SessionManagerInterfacePort $sessionManager;

    public function __construct (
        DriverRepositoryPort  $driverRepositoryPort,
        SessionManagerInterfacePort $sessionManager,
    ) 
    {
        $this->driverRepositoryPort = $driverRepositoryPort;
        $this->sessionManager = $sessionManager;
    }

    public function save($driver): bool {
       $userSession = $this->sessionManager->get('user');
       $userId = $userSession['id'];
       $providerId = $driver['provider_id'];
       $licenseNumber = $driver['license_number'];
       $licenseClass = $driver['license_class'];
       $licenseIssueDate = new \DateTimeImmutable($driver['license_issue_date']);
       $licenseExpiryDate = new \DateTimeImmutable($driver['license_expiry_date']);
       $status = $driver['status'];

       $newUser = new Driver(0, $userId, $providerId, $licenseNumber, $licenseClass, 
       $licenseIssueDate, $licenseExpiryDate, $status);

       $isAdded = $this->driverRepositoryPort->save($newUser);

       if(!$isAdded) {
        throw new \Exception("Saved not successfully");
       }

       return $isAdded ? true : false;
    }
   
}
