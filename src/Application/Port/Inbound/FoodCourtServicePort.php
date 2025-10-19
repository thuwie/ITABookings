<?php

namespace App\Application\Port\Inbound;

interface FoodCourtServicePort {
   public function createFoodCourt($foodCourt, array $imgs);
}
