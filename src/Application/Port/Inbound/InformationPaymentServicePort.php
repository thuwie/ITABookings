<?php

namespace App\Application\Port\Inbound;


interface InformationPaymentServicePort {
    public function save($informationPayment, $qr): bool;
}