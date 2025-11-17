<?php
// src/Infrastructure/Adapters/Persistence/UserRepository.php
namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\InformationPaymentPort;
use App\Domain\Entity\InformationPayment;
use Illuminate\Database\Capsule\Manager as DB;

class InformationPaymentRepository implements InformationPaymentPort {
    public function save(InformationPayment $informationPayment): InformationPayment
    {
        $id = DB::table('information_payments')->insertGetId($informationPayment->toInsertArray());
        
        // Tạo lại entity với ID vừa sinh ra
        return new InformationPayment(
            id: $id,
            userId: $informationPayment->getUserId(),
            fullName:  $informationPayment->getFullName(),
            accountNumber: $informationPayment->getAccountNumber(),
            bankName: $informationPayment->getBankName(),
            qrCode: $informationPayment->getQrCode(),
            createdAt: $informationPayment->getCreatedAt(),
            updatedAt: $informationPayment->getUpdatedAt()
        );
    }

    public function update(InformationPayment $informationPayment): bool
    {
        $data = $informationPayment->toUpdateArray();

        $updated = DB::table('information_payments')
            ->where('id', $informationPayment->getId())
            ->update($data);

        return $updated > 0;
    }

     public function getPaymentInformationByUserId(int $userId):?InformationPayment {
        $row = DB::table('information_payments')->where('user_id', $userId)->first();
        if (!$row) return null;
        return InformationPayment::fromArray((array)$row);
     }
}
