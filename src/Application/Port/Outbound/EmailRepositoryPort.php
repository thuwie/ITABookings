<?php
namespace App\Application\Port\Outbound;

interface EmailRepositoryPort {
    public function providerEmailSending($emailer): bool;
}
