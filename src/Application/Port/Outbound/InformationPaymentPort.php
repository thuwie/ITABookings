<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\InformationPayment;

interface InformationPaymentPort {
    public function save(InformationPayment $informationPayment): InformationPayment;
    public function update(InformationPayment $informationPayment): bool;
}
