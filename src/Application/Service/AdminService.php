<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\AdminServicePort;
use App\Application\Port\Outbound\UserRepositoryPort;
use App\Application\Port\Outbound\SessionManagerInterfacePort;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Application\Port\Outbound\AdminRepositoryPort;
use App\Application\Port\Outbound\EmailRepositoryPort;
use App\Domain\Entity\UserRole;
use App\Domain\Entity\ExtraCost;
use Carbon\Exceptions\Exception as ExceptionsException;
use Illuminate\Database\Capsule\Manager as DB;
use Exception;

class AdminService implements AdminServicePort {
    private SessionManagerInterfacePort $sessionManager;
    private ProviderRepositoryPort $providerRepository;
    private UserRepositoryPort $userRepository;
    private AdminRepositoryPort $adminRepository;
    private EmailRepositoryPort $emailRepository;

    public function __construct (
        SessionManagerInterfacePort $sessionManager,
        ProviderRepositoryPort $providerRepository,
        UserRepositoryPort $userRepository,
        AdminRepositoryPort $adminRepository,
        EmailRepositoryPort $emailRepository
    ) 
    {
        $this->sessionManager = $sessionManager;
        $this->providerRepository = $providerRepository;
        $this->userRepository = $userRepository;
        $this->adminRepository = $adminRepository;
        $this->emailRepository = $emailRepository;
    }

    public function approveProvider($id): bool
    {
        return DB::transaction(function () use ($id) {
            $provider = $this->providerRepository->findById($id);
            if (!$provider) {
                throw new \Exception("Không tìm thấy doanh nghiệp!!!");
            }

            $providerId = $provider->getId();
            $updatedProvider = $this->adminRepository->approveProvider($providerId);

            if (!$updatedProvider) {
                throw new \Exception("Không thể duyệt doanh nghiệp!");
            }

            $userId = $provider->getUserId();
            $userRole = new UserRole($userId, 2);
            $addedUserRole = $this->userRepository->saveRole($userRole);

            if (!$addedUserRole) {
                throw new \Exception("Không thể thêm quyền cho người dùng!");
            }
            
            $userInfo = $this->userRepository->findById($userId);
            if (!$userInfo) {
                throw new \Exception("Không tìm thấy user!");
            };

            $userEmail = $userInfo->getEmail();
            $userName = $userInfo->getFirstName() . " " . $userInfo->getLastName();
            $userId = $userInfo->getId();
            $array = (array) $updatedProvider;
            $approvedAt = $array['verified_at'];

            $emailer = ['email'=> $userEmail, 'userName' => $userName, 'userId' => $userId, 'approvedAt' => $approvedAt];
            $isEmailSent = $this->emailRepository->providerEmailSending($emailer);

            
            if (!$isEmailSent) {
                throw new \Exception("Không thể gửi email!");
            }
            return true;
        });
    }

    public function saveExtraCosts($data): bool {
       $extraCost = $data['extra_cost'];
       $platformFee = $data['platform_fee_percent'];
       $fuelPrice = $data['fuel_price'];

       $newExtraCost = new ExtraCost(0, $extraCost, $platformFee, $fuelPrice);
       $addedExtraCost = $this->adminRepository->saveExtraCosts($newExtraCost);
       if(!$addedExtraCost) {
        return false;
       }
       return true;
    }

    public function getExtraCost() {
        $data = $this->adminRepository->getExtraCost();
        return $data->toArray();
    }
}
