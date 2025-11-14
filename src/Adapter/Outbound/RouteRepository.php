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

}