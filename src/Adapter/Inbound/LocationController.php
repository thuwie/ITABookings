<?php
namespace App\Adapter\Inbound;

use App\Application\Service\LocationService;

class LocationController {
    private LocationService $service;

    public function __construct(LocationService $service) {
        $this->service = $service;
    }

    public function getProvinces() {
        header('Content-Type: application/json');
        echo json_encode($this->service->listProvinces());
    }
}
