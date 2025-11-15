<?php

namespace App\Application\Port\Inbound;


interface InformationServicePort {
    public function save($informationPayment, $qr): bool;
}