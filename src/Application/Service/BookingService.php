<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\BookingServicePort;
use App\Application\Port\Outbound\BookingRepositoryPort;
use App\Domain\Entity\Booking;
use App\Domain\Entity\VNPAY;

class BookingService implements BookingServicePort {
    private BookingRepositoryPort $booking_repository;
    public function __construct (
        BookingRepositoryPort $booking_repository
    ) 
    {
        $this->booking_repository = $booking_repository;
    }

   public function save($data, $id): array {
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
        $dataRequest = ['vnp_TxnRef' => $result['id'], 'vnp_OrderInfo' => 'Thanh toan hoa don booking-id ' . $result['id'] . ' so tien ' . number_format($totalAmount, 0, ',', '.') . ' VND',
        'vnp_OrderType' => 'booking_order', 'vnp_Amount' => $totalAmount];

        $VNPay = new VNPAY($dataRequest);
        $url = $VNPay->buildUrl();
        return $result ? ['status' => 200, 're-directUrl' => $url] : ['status:' => 500, 'message' => 'Lỗi server'];
   }
   
}
