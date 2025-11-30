<?php
namespace App\Application\Port\Outbound;

interface EmailRepositoryPort {
    public function providerEmailSending($emailer): bool;
    public function driverEmailSending($emailer): bool;
}
