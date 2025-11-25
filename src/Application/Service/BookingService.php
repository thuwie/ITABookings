<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\BookingServicePort;
use App\Application\Port\Outbound\BookingRepositoryPort;
use App\Domain\Entity\Booking;

class BookingService implements BookingServicePort {
    private BookingRepositoryPort $booking_repository;
    public function __construct (
        BookingRepositoryPort $booking_repository
    ) 
    {
        $this->booking_repository = $booking_repository;
    }

   public function save($data, $id): bool {
        $userId = (int) $id;
        $providerId = (int) $data['providerId'];
        $vehicleId = (int) $data['vehicleId'];
        $from = $data['from'];
        $to = $data['to'];
        $distance = (int) $data['distance'];
        $fromDate = new \DateTimeImmutable($data['fromDate']);
        $toDate  = new \DateTimeImmutable($data['toDate']);
        $totalDays = (int) $data['totalDays'];
        $totalAmount = (float) $data['totalAmount'];
        $status = $data['status'];

        $newBooking = new Booking(0,  $userId, $providerId, $vehicleId, $from, $to, $distance, $fromDate, $toDate, $totalDays, $totalAmount, $status);
        $result = $this->booking_repository->save($newBooking);
        
        return $result ? true : false;
   }
   
}
