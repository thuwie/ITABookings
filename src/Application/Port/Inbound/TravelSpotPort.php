<?php

namespace App\Application\Port\Inbound;

interface TravelSpotPort {
   public function createTravelSpot($travelSpot, array $imgs);
   public function getTravelSpots():array;
}
