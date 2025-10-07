<?php
namespace App\Container;

use App\Application\Service\LocationService;
use App\Adapter\Outbound\LocationApiAdapter;
use App\Adapter\Outbound\RedisCacheAdapter;
use App\Adapter\Outbound\ProvinceRepository;
use App\Application\Service\ProvinceService;
use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Outbound\ProvinceRepositoryPort;
use DI\Container;

return function (): Container {
    
    $container = new Container();
    $container->set(LocationService::class, function() {
        $cache = new RedisCacheAdapter();
        $apiPort = new LocationApiAdapter();
        return new LocationService($apiPort, $cache);
    });

        //Province Service

        // Outbound Port Binding
        $container->set(ProvinceRepositoryPort::class, function() {
            return new ProvinceRepository();
        });

        //Inbound Port Binding
        $container->set(ProvinceServicePort::class, function() use ($container) {
            return new ProvinceService($container->get(ProvinceRepositoryPort::class));
        });

     return $container;
};
