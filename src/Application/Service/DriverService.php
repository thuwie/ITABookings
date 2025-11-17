<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\DriverServicePort;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Domain\Entity\Provider;

class DriverService implements DriverServicePort {
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

    public function save($driver): bool {
        return false;
    }
   
}
