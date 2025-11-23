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

use App\Adapter\Outbound\SessionManager;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Application\Service\LoginUserUseCaseService;
use App\Application\Port\Inbound\LoginUserUseCasePort;

use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Service\ProviderService;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Adapter\Outbound\ProviderRepository;

use App\Application\Port\Inbound\InformationPaymentServicePort;
use App\Application\Service\InformationPaymentService;
use App\Application\Port\Outbound\InformationPaymentPort;
use App\Adapter\Outbound\InformationPaymentRepository;
use App\Application\Port\Outbound\UploadImageRepositoryPort;
use App\Adapter\Outbound\UploadImageRepository;
use App\Application\Port\Outbound\DriverRepositoryPort;

use App\Application\Port\Inbound\DriverServicePort;
use App\Application\Service\DriverService;
use App\Adapter\Outbound\DriverRepository;

use App\Application\Port\Inbound\AdminServicePort;
use App\Application\Port\Outbound\AdminRepositoryPort;
use App\Application\Service\AdminService;
use App\Adapter\Outbound\AdminRepository;

use App\Application\Port\Outbound\EmailRepositoryPort;
use App\Adapter\Outbound\EmailRepository;

use App\Domain\Entity\MailerService;

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
            return new UserService(
                $container->get(UserRepositoryPort::class),
                $container->get(SessionManagerInterfacePort::class)
        );
        });


        //ROUTE
        // Outbound Port Binding
        $container->set(RouteRepositoryPort::class, function() {
            return new RouteRepository();
        });

        //Inbound Port Binding
        $container->set(RouteServicePort::class, function() use ($container) {
            return new RouteService($container->get(RouteRepositoryPort::class),
            $container->get(ProviderRepositoryPort::class),
            $container->get(ProvinceRepositoryPort::class),
        );
        });


        //AUTH LOGIN
        // Outbound Port Binding
        $container->set(SessionManagerInterfacePort::class, function() {
            return new SessionManager();
        });

        //Inbound Port Binding
        $container->set(LoginUserUseCasePort::class, function() use ($container) {
            return new LoginUserUseCaseService(
                $container->get(UserRepositoryPort::class),
                $container->get(SessionManagerInterfacePort::class),
                $container->get(ProviderRepositoryPort::class),
                $container->get(DriverRepositoryPort::class)
        );
        });


        //PROVODER
        // Outbound Port Binding
        $container->set(ProviderRepositoryPort::class, function() {
            return new ProviderRepository();
        });

        //Inbound Port Binding
        $container->set(ProviderServicePort::class, function() use ($container) {
            return new ProviderService(
                $container->get(ProviderRepositoryPort::class),
                $container->get(SessionManagerInterfacePort::class),
                $container->get(InformationPaymentPort::class),
                $container->get(UploadImageRepositoryPort::class),
        );  
        });


        //PAYMENT INFORMATION
        // Outbound Port Binding
        $container->set(InformationPaymentPort::class, function() {
            return new InformationPaymentRepository();
        });

        $container->set(UploadImageRepositoryPort::class, function() {
            return new UploadImageRepository();
        });

        //Inbound Port Binding
        $container->set(InformationPaymentServicePort::class, function() use ($container) {
            return new InformationPaymentService(
                $container->get(InformationPaymentPort::class),
                $container->get(SessionManagerInterfacePort::class),
                $container->get(UploadImageRepositoryPort::class),
        );
        });


        //DRIVER
        // Outbound Port Binding
        $container->set(DriverRepositoryPort::class, function() {
            return new DriverRepository();
        });

        //Inbound Port Binding
        $container->set(DriverServicePort::class, function() use ($container) {
            return new DriverService(
                $container->get(DriverRepositoryPort::class),
                $container->get(SessionManagerInterfacePort::class),
        );
        });

        //ADMIN
        // Outbound Port Binding
        $container->set(AdminRepositoryPort::class, function() {
            return new AdminRepository();
        });

        $container->set(MailerService::class, function() {
            return new MailerService(
                'smtp.gmail.com',  // host
                'nguyenthu6605@gmail.com',             // SMTP username
                'jwxh hdrr nbtl cyza',             // SMTP password
                587,                    // port (optional, default is 587)
                'no-reply@ITABookings.com', // fromEmail (optional)
                'ITABookings',              // fromName (optional)
                'tls'                   // encryption (optional)
            );
        });

        // Inject MailerService into EmailRepository
        $container->set(EmailRepositoryPort::class, function($c) {
            return new EmailRepository($c->get(MailerService::class));
        });

        //Inbound Port Binding
        $container->set(AdminServicePort::class, function() use ($container) {
            return new AdminService(
                $container->get(SessionManagerInterfacePort::class),
                $container->get(ProviderRepositoryPort::class),
                $container->get(UserRepositoryPort::class),
                $container->get(AdminRepositoryPort::class),
                $container->get(EmailRepositoryPort::class),
        );
        });
        
     return $container;
};
