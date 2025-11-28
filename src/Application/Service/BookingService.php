<?php
namespace App\Application\Service;

use App\Application\Port\Inbound\BookingServicePort;
use App\Application\Port\Outbound\BookingRepositoryPort;
use App\Application\Port\Outbound\ProviderRepositoryPort;
use App\Domain\Entity\Booking;
use App\Domain\Entity\VNPAY;
use Illuminate\Database\Capsule\Manager as DB;

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

    public function bookingConfirming(string $data): bool
    {
        return DB::transaction(function () use ($data) {

            $parsed = $this->parseBookingNumber($data);
            if (!$parsed) return false;

            $booking_id  = (int) $parsed['booking_id'];
            $provider_id = (int) $parsed['provider_id'];
            $vehicle_id  = (int) $parsed['vehicle_id'];

            // 1. Update vehicle status
            if (!$this->providerRepository->saveVehicleStatus($vehicle_id)) {
                return false;
            }

            // 2. Find and update booking
            $booking = $this->booking_repository->findById($booking_id);
            if (!$booking) return false;

            if (!$this->booking_repository->updateById($booking_id, $booking)) {
                return false;
            }

            // 3. Get drivers of provider
            $drivers = $this->providerRepository->getDriversByProvider($provider_id);
            $driverIds = array_column($drivers, 'id');

            $driverSelected = $this->handleDriverSelectingForBooking($driverIds);
            if (!$driverSelected) return false;

            // 4. Save working history
            $historyData = [
                'driver_id' => $driverSelected->id,
                'status'    => 'assigned'
            ];

            if (!$this->providerRepository->saveDriverWorkingHistory($historyData)) {
                return false;
            }

            // 5. Save driver trip
            $trip = [
                'driver_id'  => $driverSelected->id,
                'booking_id' => $booking_id,
                'status'     => 'assigned',
                'start_time' => $booking->getFromDate(),
                'end_time'   => $booking->getToDate(),
            ];

            return $this->providerRepository->saveDriversTrips($trip);
        });
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
             $selectedDriver = $this->providerRepository->getOptimalDriver();
             return $selectedDriver;
        } else if(count($driversInBookings) < 1) {
            $selectedDriver =  $drivers[0];
            return $selectedDriver;
        } else {
            $driverIdsInHistory = array_column($driversInBookings, 'driver_id');
            $driversAreNotInBooking = $this->providerRepository->getDriversAreNotInBookingSortByASC($driverIdsInHistory);
            return $driversAreNotInBooking[0];
        };
    }

    

}
