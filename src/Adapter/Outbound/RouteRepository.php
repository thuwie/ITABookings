<?php 
namespace App\Adapter\Outbound;
use App\Application\Port\Outbound\RouteRepositoryPort;

use Illuminate\Database\Capsule\Manager as DB;
use App\Domain\Entity\Route;
use Illuminate\Support\Carbon;

class RouteRepository implements RouteRepositoryPort {
     public function save(Route $route): array {
        $id = DB::table('routes')->insertGetId([
            'from_location_code'  => $route->getFromLocationCode(),
            'destination_code'    => $route->getDestinationCode(),
            'name'       => $route->getName(),
            'distance_km' => $route->getDistanceKm(),
            'duration_min' => $route->getDurationMin(),
            'created_at' => $route->getCreatedAt() ??  Carbon::now(),
            'updated_at' => $route->getUpdatedAt() ??  Carbon::now(),
        ]);

        $routeToArray = $route->toArray();
        $routeToArray['id'] = $id;
        
        return $routeToArray;
     }

    public function getRoute(string $from, string $to): ?array {

       $sql = "SELECT *
        FROM routes
        WHERE (from_location_code = ? AND destination_code = ?)
           OR (from_location_code = ? AND destination_code = ?)
        LIMIT 1";

        $route = DB::selectOne($sql, [$from, $to, $to, $from]); 

        if (!$route) {
            return null; // no route found
        }

        // Convert stdClass to array
        $routeArray = (array) $route;

        return $routeArray;
    }

    public function getProvidersWithVehicles () : array {
        $result = DB::table('providers')
        ->join('vehicles', 'vehicles.provider_id', '=', 'providers.user_id')
        ->select(
            // Provider fields
            'providers.id as provider_id',
            'providers.name as provider_name',
            'providers.logo_url as provider_logo',

            // Vehicle fields
            'vehicles.id as vehicle_id',
            DB::raw("CONCAT(vehicles.brand, ' ', vehicles.model) AS vehicle_name"),
            'vehicles.seat_count',
            'vehicles.fuel_consumption',
            'vehicles.maintenance_per_km'
        )
        ->get();

        return $result->toArray();
    }

}