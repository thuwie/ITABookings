<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\BookingServicePort;
use App\Application\Port\Outbound\BookingRepositoryPort;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Domain\Entity\Booking;
use App\Domain\Entity\VNPAY;

class BookingService implements BookingServicePort {
    private BookingRepositoryPort $booking_repository;
    private ProviderRepositoryPort $providerRepository;
    public function __construct (
        BookingRepositoryPort $booking_repository,
        ProviderRepositoryPort $providerRepository
    ) 
    {
        $this->booking_repository = $booking_repository;
        $this->providerRepository = $providerRepository;
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
        $dataRequest = ['vnp_TxnRef' => $result['id'], 'vnp_OrderInfo' => 'Thanh toan hoa don booking: ' . $result['id'] . 
        '-' . $providerId . '-'  . $vehicleId . ' .So tien ' . number_format($totalAmount, 0, ',', '.') . ' VND. ',
        'vnp_OrderType' => 'booking_order', 'vnp_Amount' => $totalAmount];

        $VNPay = new VNPAY($dataRequest);
        $url = $VNPay->buildUrl();
        return $result ? ['status' => 200, 're-directUrl' => $url] : ['status:' => 500, 'message' => 'Lỗi server'];
   }

    public function bookingConfirming(string $data): bool {
        $result = $this->parseBookingNumber($data);
        if($result) {
            $booking_id = (int) $result['booking_id'];
            $provider_id = (int) $result['provider_id'];
            $vehicle_id = (int) $result['vehicle_id'];
            $this->providerRepository->saveVehicleStatus($vehicle_id);
            $bookingNeedToUpdate = $this->booking_repository->findById($booking_id);
            if($bookingNeedToUpdate) {
               $updatedBooking = $this->booking_repository->updateById($booking_id, $bookingNeedToUpdate);
               if($updatedBooking) {
                  $drivers = $this->providerRepository->getDriversByProvider($provider_id);
                  $ids = array_column($drivers, 'id');
                  $driverSelected = $this->handleDriverSelectingForBooking($ids);
               }
            };
        

        }

        return false;
    }

    public function parseBookingNumber($text): array {
        // Lấy ra chuỗi dạng số số số
        preg_match('/(\d+\s*-\s*\d+\s*-\s*\d+)/', $text, $matches);

        if (!empty($matches[1])) {
            $bookingNumbers = $matches[1];

            // Tách thành mảng
            $numbers = array_map('trim', explode('-', $bookingNumbers));

            $booking_id = $numbers[0];
            $provider_id = $numbers[1];
            $vehicle_id = $numbers[2];

              return ['booking_id' => $booking_id, 'provider_id' => $provider_id, 'vehicle_id' => $vehicle_id];
            }
        return [];
    }

    public function handleDriverSelectingForBooking(array $ids) {
        $drivers = $this->providerRepository->getDriversByIds($ids);
        $driversInBookings = $this->providerRepository->getDriverWorkingHistory($ids);
        if(count($drivers) === count( $driversInBookings)) {
                
        } else {

        }
    }
}
