<?php

namespace App\Application\Port\Inbound;

interface LoginUserUseCasePort {
   public function login(string $email, string $password);
}
