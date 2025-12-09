<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\RouteServicePort;
use  App\Application\Port\Outbound\RouteRepositoryPort;
use  App\Application\Port\Outbound\ProviderRepositoryPort;
use  App\Application\Port\Outbound\ProvinceRepositoryPort;
use App\Domain\Entity\Route;
use Illuminate\Support\Carbon;
use App\Domain\ValueObject\MinuteHourConverter;

class RouteService implements RouteServicePort {
    private RouteRepositoryPort $routeRepositoryPort;
    private ProviderRepositoryPort $providerRepository;
    private ProvinceRepositoryPort $provinceRepository;



    public function __construct(RouteRepositoryPort $routeRepository,  ProviderRepositoryPort $providerRepository,
    ProvinceRepositoryPort $provinceRepository
    ) {
        $this->routeRepositoryPort = $routeRepository;
        $this->providerRepository = $providerRepository;
        $this->provinceRepository = $provinceRepository;
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

    public function findRoutes(string $from, string $to) {
        $route = $this->routeRepositoryPort->getRoute($from, $to);
        return $route
        ? ['status' => 'success', 'data' => $route, 'message' => 'Route found successfully']
        : ['status' => 'failed', 'message' => 'Route not found'];
    }

    public function findVehiclesByRoute($data): array {
        $from = (int)$data['from']; 
        $to = $data['to']; 
        $seat_counting_filter = (int) $data['seat_counting']; 
        $provider_filter =  (int) $data['provider']; 

        $fromPlace = $this->provinceRepository->findById($from);
        $toPlace = $this->provinceRepository->findById($to);

        $placeFromName = $fromPlace['name'];
        $placeToName = $toPlace['name'];


        $placeFromOfCode = $fromPlace['code'];
        $placeToOfCode = $toPlace['code'];

        $route = $this->routeRepositoryPort->getRoute($placeFromOfCode, $placeToOfCode);
        $routeName = $route['name'];

        $routeInformation = new \stdClass();
        $routeInformation->from = $placeFromName;
        $routeInformation->to = $placeToName ;
        $routeInformation->route_name = $routeName ;
        $routeInformation->km = $route['distance_km'] ;



        $providersWithVehicles = null;
        $list_driver_costs = [];
        $result = [];       // final array keyed by provider_id
        $km = $route['distance_km'];
        $duration =  $route['duration_min'];
        $hoursUsed = MinuteHourConverter::minutesToHoursDecimal($duration);

        if ( $provider_filter && $seat_counting_filter) {
            $providersWithVehicles = $this->providerRepository->getProvidersWithVehicles($seat_counting_filter,  $provider_filter);
        }  else if($seat_counting_filter) {
            $providersWithVehicles = $this->providerRepository->getProvidersWithVehicles($seat_counting_filter);
        } else if ( $provider_filter) {
            $providersWithVehicles = $this->providerRepository->getProvidersWithVehicles(null,  $provider_filter);
        }
        else {
            $providersWithVehicles = $this->providerRepository->getProvidersWithVehicles();
        };

        $providersRelatedCosts = $this->providerRepository->providersRelatedCosts();
        $extraCosts = $this->providerRepository->getExtraCosts();

        // Prepare driver cost + profit margin per provider
        foreach ($providersRelatedCosts as $cost) {
            $providerId = $cost->provider_id;
            $driverFeePerHour = (int) $cost->driver_fee_per_hour;
            $profitMargin = (int) $cost->profit_margin;

            $list_driver_costs[$providerId] = [
                'driver_cost'   => $hoursUsed * $driverFeePerHour,
                'profit_margin' => $profitMargin
            ];
        }

        // Loop through all vehicles
        foreach ($providersWithVehicles as $vehicle) {
            $providerId = $vehicle->provider_id;
            if (!isset($list_driver_costs[$providerId])) {
                // Skip provider without cost data
                continue;
            }

            $fuel_cost = ($km  / 100) * (float)$vehicle->fuel_consumption * (float)$extraCosts->fuel_price;
            $maintenance_cost = $km  * (int)$vehicle->maintenance_per_km;
            $driver_cost = $list_driver_costs[$providerId]['driver_cost'];
            $fixed_cost = (int)$extraCosts->extra_cost;

            $operating_cost = $fuel_cost + $maintenance_cost + $driver_cost + $fixed_cost;

            $total_profit_both = (int)$extraCosts->platform_fee_percent + $list_driver_costs[$providerId]['profit_margin'];
            $price_per_day = $operating_cost * (1 + $total_profit_both / 100);
            $price_per_day = round($price_per_day, 0);

            // Create vehicle object
            $new_vehicle = new \stdClass();
            $new_vehicle->id = $vehicle->vehicle_id;
            $new_vehicle->name = $vehicle->vehicle_name;
            $new_vehicle->seat_counting = $vehicle->seat_count;
            $new_vehicle->price_per_day = $price_per_day;

            // If provider does not exist in result, create it
            if (!isset($result[$providerId])) {
                $provider = new \stdClass();
                $provider->id = $vehicle->provider_id;
                $provider->name = $vehicle->provider_name;
                $provider->logo = $vehicle->provider_logo;

                $result[$providerId] = new \stdClass();
                $result[$providerId]->provider = $provider;
                $result[$providerId]->vehicles = [$new_vehicle];
            } else {
                // Provider exists → append vehicle
                $result[$providerId]->vehicles[] = $new_vehicle;
            }
        }

        $result = array_values($result); 

       $updateResult = ['route' => $routeInformation, 'result' =>$result];

       return $updateResult;
         
    }

    
}
