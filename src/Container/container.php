<?php
namespace App\Container;

use Psr\Container\ContainerInterface;
use App\Application\Service\LocationService;
use App\Adapter\Outbound\LocationApiAdapter;
use App\Adapter\Outbound\RedisCacheAdapter;
use DI\Container;

return function (): Container {
    
    $container = new Container();
    $container->set(LocationService::class, function() {
        $cache = new RedisCacheAdapter();
        $apiPort = new LocationApiAdapter();
        return new LocationService($apiPort, $cache);
    });

     return $container;
};
