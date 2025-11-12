<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\RouteServicePort;
use  App\Application\Port\Outbound\RouteRepositoryPort;
use App\Domain\Entity\Route;
use Illuminate\Support\Carbon;

class RouteService implements RouteServicePort {
    private RouteRepositoryPort $routeRepositoryPort;

    public function __construct(RouteRepositoryPort $routeRepository) {
        $this->routeRepositoryPort = $routeRepository;
    }
    
    public function createRoute($route) {
        $fromLocationCode = $route['from_location_code'];
        $destinationCode = $route['destination_code'];
        $routeName = $route['name'];
        $distance = isset($route['distance_km']) ? (int)$route['distance_km'] : null;
        $duration = isset($route['duration_min']) ? (int)$route['duration_min'] : null;

        $newRoute = new Route(0, $fromLocationCode, $destinationCode, $routeName, 
        $distance, $duration, 
        Carbon::now()->toImmutable(), 
        Carbon::now()->toImmutable());

        $result = $this->routeRepositoryPort->save($newRoute);

        return $result
        ? ['status' => 'success', 'message' => 'Route saved successfully']
        : ['status' => 'failed', 'message' => 'Route saved unsuccessfully'];
    }
    
}
