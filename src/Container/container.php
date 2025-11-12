<?php
namespace App\Container;

use App\Adapter\Outbound\ProvinceRepository;
use App\Application\Service\ProvinceService;
use App\Application\Port\Inbound\ProvinceServicePort;
use App\Application\Port\Outbound\ProvinceRepositoryPort;

use App\Application\Port\Inbound\TravelSpotPort;
use App\Application\Service\TravelSpotService;
use App\Application\Port\Outbound\TravelSpotRepositoryPort;
use App\Adapter\Outbound\TravelRepositoryAdapter;

use App\Application\Port\Outbound\FoodCourtRepositoryPort;
use App\Adapter\Outbound\FoodCourtRepository;
use App\Application\Service\FoodCourtService;
use App\Application\Port\Inbound\FoodCourtServicePort;

use App\Application\Port\Outbound\UserRepositoryPort;
use App\Application\Port\Inbound\UserServicePort;
use App\Application\Service\UserService;
use App\Adapter\Outbound\UserRepository;

use App\Application\Port\Outbound\RouteRepositoryPort;
use App\Application\Port\Inbound\RouteServicePort;
use App\Application\Service\RouteService;
use App\Adapter\Outbound\RouteRepository;

use DI\Container;

return function (): Container {
    
    $container = new Container();
        //PROVINCE SERVICES
        // Outbound Port Binding
        $container->set(ProvinceRepositoryPort::class, function() {
            return new ProvinceRepository();
        });

        //Inbound Port Binding
        $container->set(ProvinceServicePort::class, function() use ($container) {
            return new ProvinceService(
            $container->get(ProvinceRepositoryPort::class),
            $container->get(TravelSpotRepositoryPort::class),
             $container->get(FoodCourtRepositoryPort::class)
        );
        });


        //TRAVEL SPOT SERVICES
         // Outbound Port Binding
        $container->set(TravelSpotRepositoryPort::class, function() {
            return new TravelRepositoryAdapter();
        });

        //Inbound Port Binding
        $container->set(TravelSpotPort::class, function() use ($container) {
            return new TravelSpotService($container->get(TravelSpotRepositoryPort::class));
        });


        //FOOD COURT SERVICES
         // Outbound Port Binding
        $container->set(FoodCourtRepositoryPort::class, function() {
            return new FoodCourtRepository();
        });

        //Inbound Port Binding
        $container->set(FoodCourtServicePort::class, function() use ($container) {
            return new FoodCourtService($container->get(FoodCourtRepositoryPort::class));
        });

        //USERS
        // Outbound Port Binding
        $container->set(UserRepositoryPort::class, function() {
            return new UserRepository();
        });

        //Inbound Port Binding
        $container->set(UserServicePort::class, function() use ($container) {
            return new UserService($container->get(UserRepositoryPort::class));
        });

        //ROUTE
        // Outbound Port Binding
        $container->set(RouteRepositoryPort::class, function() {
            return new RouteRepository();
        });

        //Inbound Port Binding
        $container->set(RouteServicePort::class, function() use ($container) {
            return new RouteService($container->get(RouteRepositoryPort::class));
        });
        
     return $container;
};
