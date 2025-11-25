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

}
