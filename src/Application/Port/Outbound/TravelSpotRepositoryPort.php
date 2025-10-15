<?php
namespace App\Application\Port\Outbound;
use App\Domain\Entity\TravelSpot;

interface TravelSpotRepositoryPort {
     public function save(TravelSpot $travelSpot): array;
}
