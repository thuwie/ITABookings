<?php

namespace App\Application\Port\Inbound;

use App\Domain\Entity\Provider;

interface ProviderServicePort {
    public function save($provider, $logo): bool;
}