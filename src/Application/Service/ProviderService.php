<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\ProviderServicePort;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Application\Port\Outbound\InformationPaymentPort;
use App\Application\Port\Outbound\UploadImageRepositoryPort;
use App\Application\Port\Outbound\BookingRepositoryPort;
use App\Application\Port\Outbound\UserRepositoryPort;
use App\Application\Port\Outbound\EmailRepositoryPort;
use Illuminate\Database\Capsule\Manager as DB;
use App\Domain\Entity\Provider;
use App\Domain\Entity\Vehicle;
use App\Domain\Entity\Utility;
use App\Domain\Entity\VehicleImage;
use App\Domain\Entity\VehicleUtility;
use App\Domain\Entity\CostsRelatedProvider;
use App\Domain\Entity\UserRole;



class ProviderService implements ProviderServicePort {
    private ProviderRepositoryPort  $providerRepositoryPort;
    private SessionManagerInterfacePort $sessionManager;
    private InformationPaymentPort $paymentInformation;
    private UploadImageRepositoryPort $uploadImageRepository;
    private BookingRepositoryPort $bookingRepository;
    private UserRepositoryPort $userRepository;
    private EmailRepositoryPort $emailRepository;


    public function __construct (
        ProviderRepositoryPort  $providerRepositoryPort,
        SessionManagerInterfacePort $sessionManager,
        InformationPaymentPort $paymentInformation,
        UploadImageRepositoryPort $uploadImageRepository,
        BookingRepositoryPort $bookingRepository,
        UserRepositoryPort $userRepository,
        EmailRepositoryPort $emailRepository
    ) 
    {
        $this->providerRepositoryPort = $providerRepositoryPort;
        $this->sessionManager = $sessionManager;
        $this->paymentInformation = $paymentInformation;
        $this->uploadImageRepository = $uploadImageRepository;
        $this->bookingRepository = $bookingRepository;
        $this->userRepository = $userRepository;
        $this->emailRepository = $emailRepository;
    }

    public function save($provider, $logo): bool { 
        return DB::transaction(function () use ($provider, $logo) {
        $name = $provider['name'];
        $email = $provider['email'];
        $phoneNumber = $provider['phone_number'];
        $address = $provider['address'];
        $province = (int) $provider['province_id'];
        $description = $provider['description'];
        $userSession = $this->sessionManager->get('user');
        $userId = $userSession['id'];
        $newProvider = new Provider(0, $userId, $name, $email, null, $description, $phoneNumber, $address, $province);

        $newProvider = $this->providerRepositoryPort->save($newProvider);

        if(!$newProvider) {
             throw new \Exception('Đăng ký thất bại');
        };

        $uploadedLogoUrl = $this->providerRepositoryPort->saveLogo($logo, $newProvider->getName());

        if(!$uploadedLogoUrl) {
             throw new \Exception('Lưu logo thất bại');
        }

        $result = $this->providerRepositoryPort->savePathLogo($uploadedLogoUrl, $newProvider);

        if($result) {
            $userSession['temporary_role'] = "provider";
            $this->sessionManager->set('user', $userSession);
        }
        
        return $result ? true : false;

         });
    }
   
    public function getRegisterForm(): array {
        $userSession = $this->sessionManager->get("user");
        $userId = (int) $userSession['id'];

        $formInformation = $this->providerRepositoryPort->findByUserIdWithVerifyFallback($userId);
        $paymentInformation = $this->paymentInformation->getPaymentInformationByUserId($userId);
        
        if(!$formInformation || !$paymentInformation) {
              throw new \Exception('Not found registered information !!!');
        }

        $data = [
            'registeredInformation' => $formInformation->toArray(),
            'paymentInformation' =>$paymentInformation->toArray()
        ];

        return $data;
    }

    public function getProviders($filter_value): array {
        $result = $this->providerRepositoryPort->getProvidersByVerified($filter_value);
        return $result;
    }

    public function getProviderById(int $id): array {
        $result = $this->providerRepositoryPort->findById($id)->toArray();
        if(!$result) {
            throw new \Exception('Not found provider !!!');
        }

        return $result;
    }

    public function saveVehicle($vehicleInfo, $imgs, $id): bool
    {
        return DB::transaction(function () use ($vehicleInfo, $imgs, $id) {

            // Extract data
            $brand = $vehicleInfo['brand'];
            $model = $vehicleInfo['model'];
            $license_plate = $vehicleInfo['license_plate'];
            $year_of_manufacture = $vehicleInfo['year_of_manufacture'];
            $seat_counting = $vehicleInfo['seat_counting'];
            $fuel_consumption = (float) $vehicleInfo['fuel_consumption'];
            $maintenance_per_km = (float) $vehicleInfo['maintenance_per_km'];
            $description = $vehicleInfo['description']; 
            $isNewUtility =  $vehicleInfo['newUtility'];
            $utilities =  $vehicleInfo['utilities'];
            $provider_id = (int) $id;

            // 1. Insert Vehicle
            $newVehicle = new Vehicle(
                0, 
                $description, 
                $license_plate, 
                $brand, 
                $model, 
                $year_of_manufacture,
                $seat_counting, 
                $provider_id, 
                $fuel_consumption, 
                $maintenance_per_km
            );

            $addedVehicle = $this->providerRepositoryPort->saveVehicle($newVehicle);

            if (!$addedVehicle) {
                throw new \Exception("Thêm mới phương tiện không thành công!!!");
            }

            $vehicleId = $addedVehicle['id'];
            $nameVehicle = $addedVehicle['model'];

            // 2. Upload images (this must also be rollback-safe)
            $uploadedImgs = $this->uploadImageRepository->saveMultipleOnes($imgs, 'vehicles', $nameVehicle);

            if (!$uploadedImgs) {
                throw new \Exception("Thêm mới hình ảnh phương tiện không thành công!!!");
            }

            // 3. Convert upload results → entity list
            $arrayVehicleImgs = $this->buildVehicleImgEntities($uploadedImgs, $vehicleId);

            if (!$arrayVehicleImgs) {
                throw new \Exception("Chuyển đổi hình ảnh thất bại!!!");
            }

            // 4. Save vehicle images into DB
            $this->providerRepositoryPort->saveVehicleImgs($arrayVehicleImgs);

            // 5. Utilities
            if ($isNewUtility) {

                // 5.1 Insert new utilities
                $rows = [];
                foreach ($utilities as $utility) {
                    $rows[] = new Utility(0, $utility);
                }

                $addUtilities = $this->providerRepositoryPort->saveUtilities($rows);

                if (!$addUtilities) {
                    throw new \Exception("Thêm mới tiện ích không thành công!!!");
                }

                // 5.2 Save vehicle_utilities
                $vehicleWithUtilities = $this->buildVehicleWithUtilities($addUtilities, $vehicleId);

                $addedVehicleWithUtilities = 
                    $this->providerRepositoryPort->saveVehicleWithUtilities($vehicleWithUtilities);

                if (!$addedVehicleWithUtilities) {
                    throw new \Exception("Thêm mới liên kết tiện ích thất bại!!!");
                }

            } else {

                // 6. Use existing utilities
                $vehicleWithUtilities = $this->buildVehicleWithUtilities($utilities, $vehicleId);

                $addedVehicleWithUtilities = 
                    $this->providerRepositoryPort->saveVehicleWithUtilities($vehicleWithUtilities);

                if (!$addedVehicleWithUtilities) {
                    throw new \Exception("Thêm mới liên kết tiện ích thất bại!!!");
                }
            }

            // All success → commit
            return true;
        });
    }

    public function buildVehicleImgEntities(array $uploadedImgs, int $vehicleId): array
    {
        $rows = [];

        foreach ($uploadedImgs as $img) {
            $rows[] =  new VehicleImage(0, $vehicleId,  $img['url'], $img['file_name']);
        }

        return $rows;
    }

    public function buildVehicleWithUtilities(array $utilities, int $vehicleId): array {
        $rows = [];
        foreach($utilities as $utility) {
            $rows[] = new VehicleUtility(0, $vehicleId, $utility);
        };
        return $rows;
    }

    public function getUtilities(): array {
        $result = $this->providerRepositoryPort->getUtilities();
        return $result;
    }

    public function saveProviderExtraCosts($data, $providerId): bool {
        $driver_fee_per_hour = (float) $data['driver_fee_per_hour'];
        $profit_margin = (float)  $data['profit_margin'];
        $id = (int)  $providerId; 
        $entity = new CostsRelatedProvider (0, $id,  $driver_fee_per_hour, $profit_margin);
        $result = $this->providerRepositoryPort->saveProviderExtraCosts($entity);
        if(!$result) {
            return false;
        }
        return true;
    }

    public function getSeatCounting():array {
        $result = $this->providerRepositoryPort->getSeatCounting();
        return $result;
    }

    public function getProviderWithVehicle($providerId, $vehicleId): array {
        $result = $this->providerRepositoryPort->getProviderWithVehicle($providerId, $vehicleId);
        return $result;
    }

    public function getProfile():array {
        $sessionManager = $this->sessionManager->get('user');
        $providerId = (int) $sessionManager['id'];
        $provider = $this->providerRepositoryPort->findByUserId($providerId);
        $result = ['id' => $provider->getId() ,'name' =>  $provider->getName(), 'avt' =>$provider->getLogoUrl()];
        return $result;
    }

    public function getBookings(): array {
        $sessionManager = $this->sessionManager->get('user');
        $providerId = (int) $sessionManager['id'];
        $result = $this->bookingRepository->getBookings( $providerId );
        return $result;
    }

    public function getVehiclesByProviderId($providerId): array {
        $parseProviderId = (int) $providerId;
        $result = $this->providerRepositoryPort->getVehiclesWithStatusByProviderId($parseProviderId);
        return $result;
    }   

    public function getDriversByProviderId( $providerId, $filter_value): array {
        $parseFilterValue =  $filter_value === 'true' ? true : false;
        $parseProviderId = (int) $providerId;
        $result = $this->providerRepositoryPort->getDriversByProviderId($parseProviderId, $parseFilterValue);
        return $result;
    }

     public function verifyDriver($providerId, $driverId): bool {
        return DB::transaction(function () use ($providerId, $driverId) {
            $parseProviderId = (int) $providerId;
            $parseDriverId = (int) $driverId;
            $updatedDriver = $this->providerRepositoryPort->updateDriver($parseProviderId,  $parseDriverId);
            $provider = $this->providerRepositoryPort->findById($parseProviderId);
            if($updatedDriver && $provider) {
                $userId =  (int) $updatedDriver['user_id'];
                $user = $this->userRepository->findById($userId);
                if($user) {
                    $userRole = new UserRole($userId, 3);
                    $savedRole = $this->userRepository->saveRole($userRole);
                    if($savedRole) {
                        $emailUser = $user->getEmail();
                        $userName = $user->getFirstName() . '' . $user->getLastName();
                        $business = $provider->getName();
                        $confirmedAt = $updatedDriver['verified_at'];

                        $emailer = ['email' => $emailUser, 'userName' => $userName, 'approvedAt' => $confirmedAt, 'businessName' =>$business];
                        $isSent = $this->emailRepository->driverEmailSending($emailer); 
                        return $isSent && true;
                    };
                }
            }
            return false;
            });
     }
}
    