<?php

namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\BookingRepositoryPort;
use App\Domain\Entity\Booking;
use Illuminate\Database\Capsule\Manager as DB;

class BookingRepository implements BookingRepositoryPort {
   public function save(Booking $data): array
    {
        // Convert entity to array for insert
        $insertData = [
            'user_id'       => $data->getUserId(),
            'provider_id'   => $data->getProviderId(),
            'vehicle_id'    => $data->getVehicleId(),
            'from_location' => $data->getFromLocation(),
            'destination'   => $data->getDestination(),
            'distance'      => $data->getDistance(),
            'from_date'     => $data->getFromDate()->format('Y-m-d H:i:s'),
            'to_date'       => $data->getToDate()->format('Y-m-d H:i:s'),
            'total_days'    => $data->getTotalDays(),
            'total_amount'  => $data->getTotalAmount(),
            'status'        => $data->getStatus(),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        // Insert and get inserted id
        $id = DB::table('bookings')->insertGetId($insertData);

        if (!$id) {
            return [];  // or throw exception
        }

        // Return the inserted booking with all fields
        $result = DB::table('bookings')->where('id', $id)->first();

        // Convert stdClass → array
        return (array) $result;
    }

    public function findById(int $id): ?Booking
    {
        // Lấy bản ghi từ database
        $row = DB::table('bookings')->where('id', $id)->first();

        // Không tìm thấy → trả về null
        if (!$row) {
            return null;
        }

        // Convert stdClass -> array
        $data = (array) $row;

        // Tạo entity Booking từ dữ liệu
        $booking = new Booking(
            0,
            $data['user_id'],
            $data['provider_id'],
            $data['vehicle_id'],
            $data['from_location'],
            $data['destination'],
            $data['distance'],
            new \DateTimeImmutable($data['from_date']),
            new \DateTimeImmutable($data['to_date']),
            $data['total_days'],
            $data['total_amount'],
            $data['status']
        );

        return $booking;
    }


    public function updateById(int $id, Booking $data): array
    {
        // Chuẩn bị dữ liệu để update
        $updateData = [
            'user_id'       => $data->getUserId(),
            'provider_id'   => $data->getProviderId(),
            'vehicle_id'    => $data->getVehicleId(),
            'from_location' => $data->getFromLocation(),
            'destination'   => $data->getDestination(),
            'distance'      => $data->getDistance(),
            'from_date'     => $data->getFromDate()->format('Y-m-d H:i:s'),
            'to_date'       => $data->getToDate()->format('Y-m-d H:i:s'),
            'total_days'    => $data->getTotalDays(),
            'total_amount'  => $data->getTotalAmount(),
            'status'        => $data->getStatus(),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        // Thực hiện update
        $updated = DB::table('bookings')
            ->where('id', $id)
            ->update($updateData);

        // Nếu không update được (0 dòng bị ảnh hưởng)
        if (!$updated) {
            return [];
        }

        // Lấy bản ghi sau khi update
        $result = DB::table('bookings')->where('id', $id)->first();

        // Trả về dạng array
        return (array) $result;
    }


}
