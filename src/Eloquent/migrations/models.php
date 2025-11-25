<?php
require __DIR__ . '/../../bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;


// Danh sách table để tạo
$tables = [
    'provinces' => function ($table) {
        $table->increments('id');
        $table->string('code', 50)->unique();
        $table->string('name');
        $table->string('type')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'province_images' => function ($table) {
        $table->increments('id');
        $table->unsignedInteger('province_id');
        $table->string('url');
        $table->string('publicUrl')->nullable();
        $table->timestamps();

        $table->foreign('province_id')
              ->references('id')
              ->on('provinces')
              ->onDelete('cascade');
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'province_ratings' => function ($table) {
        $table->increments('id');
        $table->unsignedInteger('province_id');
        $table->time('open_time')->nullable();
        $table->time('close_time')->nullable();
        $table->decimal('average_rating', 3, 2)->default(0); 
        $table->unsignedInteger('total_rates')->default(0);
        $table->decimal('price_from', 12, 2)->nullable();
        $table->decimal('price_to', 12, 2)->nullable();
        $table->timestamps();
        // Charset & Collation
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    
     'travel_spots' => function ($table) {
        $table->increments('id');                  // PK
        $table->string('name');                   // Tên địa điểm
        $table->text('description')->nullable();  // Mô tả
        $table->unsignedInteger('province_id');   // FK tới provinces
        $table->time('open_time')->nullable();    // Giờ mở cửa
        $table->time('close_time')->nullable();   // Giờ đóng cửa
        $table->decimal('average_rate', 3, 2)->default(0);  // Điểm trung bình (0-5)
        $table->decimal('price_from', 10, 2)->nullable();   // Giá thấp nhất
        $table->decimal('price_to', 10, 2)->nullable();     // Giá cao nhất
        $table->unsignedInteger('total_rates')->default(0); // Tổng số lượt đánh giá
        $table->string('full_address')->nullable();         // Địa chỉ chi tiết
        $table->timestamps();

        $table->foreign('province_id')
              ->references('id')
              ->on('provinces')
              ->onDelete('cascade');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'travel_imgs' => function ($table) {
        $table->increments('id');                          // PK
        $table->unsignedInteger('id_travel_spot');         // FK tới travel_spots
        $table->string('url');                             // Đường dẫn ảnh
        $table->string('publicUrl')->nullable();           // Link public (nếu có)
        $table->timestamps();

        $table->foreign('id_travel_spot')
              ->references('id')
              ->on('travel_spots')
              ->onDelete('cascade');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },
    'food_courts' => function ($table) {
        $table->increments('id');                        // PK
        $table->string('name');                          // Tên khu ăn uống
        $table->text('description')->nullable();         // Mô tả
        $table->string('address')->nullable();           // Địa chỉ cụ thể
        $table->unsignedInteger('province_id');          // FK tới provinces
        $table->unsignedInteger('travel_spot_id');       
        $table->time('open_time')->nullable();           // Giờ mở
        $table->time('close_time')->nullable();          // Giờ đóng
        $table->decimal('average_star', 3, 2)->default(0);  // Điểm trung bình
        $table->unsignedInteger('total_rates')->default(0); // Tổng đánh giá
        $table->decimal('price_from', 10, 2)->nullable();    // Giá từ
        $table->decimal('price_to', 10, 2)->nullable();      // Giá đến
        $table->timestamps();

        $table->foreign('province_id')
            ->references('id')
            ->on('provinces')
            ->onDelete('cascade');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },
    'food_court_images' => function ($table) {
        $table->increments('id');                     // PK
        $table->unsignedInteger('food_court_id');          // FK tới food_courts
        $table->string('url');                       // Đường dẫn file
        $table->string('public_url')->nullable();    // Link public (nếu có)
        $table->timestamps();

        $table->foreign('food_court_id')
            ->references('id')
            ->on('food_courts')
            ->onDelete('cascade');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'routes' => function ($table) {
        $table->increments('id');
        $table->string('from_location_code', 50);
        $table->string('destination_code', 50);
        $table->string('name');
        $table->integer('distance_km')->unsigned()->nullable();
        $table->integer('duration_min')->unsigned()->nullable();
        $table->timestamps();

        // Thiết lập charset & collation
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'users' => function ($table) {
        $table->increments('id');

        $table->string('first_name', 100);
        $table->string('last_name', 100);
        $table->string('password', 255);
        $table->string('email', 150)->unique();

        $table->string('phone_number', 10)->nullable();
        $table->string('portrait', 255)->nullable();

        $table->enum('gender', ['male', 'female', 'other'])
            ->default('male');

        $table->date('date_of_birth')->nullable();
        $table->string('CCCD', 20)->nullable();
        $table->string('address', 255)->nullable();

        $table->unsignedInteger('province_id')->nullable();
        $table->timestamps();

        // Charset + collation
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'user_roles' => function ($table) {
        $table->unsignedInteger('user_id');
        $table->unsignedInteger('role_id');
        $table->timestamps();

        $table->unique(['user_id', 'role_id']);

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },


    'providers' => function ($table) {
        $table->increments('id');
        $table->unsignedInteger('user_id'); // Liên kết users.id
        $table->string('name', 255);
        $table->string('logo_url', 255)->nullable();
        $table->text('description')->nullable();
        $table->string('email', 150);
        $table->string('phone_number', 10)->nullable();
        $table->string('address', 255)->nullable();
        $table->unsignedInteger('province_id')->nullable();
        $table->decimal('average_rates', 10, 2)->nullable();
        $table->unsignedInteger('rating_count')->default(0);
        $table->timestamp('verified_at')->nullable();

        // created_at & updated_at
        $table->timestamps();

        // Unique: user_id chỉ có 1 provider
        $table->unique('user_id');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'banned_providers' => function ($table) {
        $table->increments('id');

        $table->unsignedInteger('provider_id'); 
        $table->tinyInteger('is_banned')->default(1);
        $table->text('reason')->nullable();
        $table->timestamp('banned_at')->useCurrent();

        // created_at & updated_at
        $table->timestamps();
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'information_payments' => function ($table) {
        $table->increments('id');

        $table->unsignedInteger('user_id'); // Liên kết đến users.id
        $table->string('full_name', 255);
        $table->string('account_number', 50);
        $table->string('bank_name', 100);
        $table->string('qr_code', 255)->nullable();

        // created_at & updated_at
        $table->timestamps();

        // Charset & collation
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';

    },

    'drivers' => function ($table) {
        $table->increments('id');
        $table->unsignedInteger('user_id');        
        $table->unsignedInteger('provider_id');

        $table->string('license_number', 50);
        $table->string('license_class', 5);
        $table->date('license_issue_date');
        $table->date('license_expiry_date');

        $table->enum('status', ['valid','expired','suspended','revoked'])
            ->default('valid');


        $table->decimal('average_rates', 10, 2)   
            ->nullable();

        $table->unsignedInteger('rating_count')   
            ->default(0);

        $table->timestamp('verified_at')->nullable();

        // created_at & updated_at
        $table->timestamps();

        // UNIQUE
        $table->unique('user_id', 'unique_user');

        // Charset & collation
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'vehicles' => function ($table) {
        $table->increments('id');

        $table->text('description');

        $table->string('license_plate', 20);
        $table->string('brand', 100);
        $table->string('model', 100);

        $table->year('year_of_manufacture');

        $table->tinyInteger('seat_count')->unsigned();

        $table->unsignedInteger('provider_id'); // FK

        $table->decimal('fuel_consumption', 5, 2); // L/100km
        $table->decimal('maintenance_per_km', 10, 2);

        $table->timestamps();

        // UNIQUE
        $table->unique('license_plate', 'unique_license_plate');

        // Charset & Collation
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'vehicle_imgs' => function ($table) {
        $table->increments('id');

        $table->unsignedInteger('vehicle_id');

        $table->string('url', 255);
        $table->string('public_url', 255)->nullable();

        $table->timestamps();

        // Charset
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },


    'utilities' => function ($table) {
        $table->increments('id');

        $table->string('name', 100);

        $table->timestamps();

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'vehicle_utilities' => function ($table) {
        $table->increments('id');

        $table->unsignedInteger('utility_id');
        $table->unsignedInteger('vehicle_id');

        $table->timestamps();

        // UNIQUE
        $table->unique(['utility_id', 'vehicle_id'], 'unique_vehicle_utility');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'extra_costs' => function ($table) {
        $table->increments('id');
        $table->decimal('extra_cost', 10, 2); // Chi phí khác, cầu cống
        $table->decimal('platform_fee_percent', 5, 2); // % hoa hồng hệ thống
        $table->decimal('fuel_price', 10, 2); // Giá nhiên liệu hiện tại trên thị trường

        $table->timestamps();

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },


    'costs_related_providers' => function ($table) {
        $table->increments('id');

        $table->unsignedInteger('provider_id');
        $table->decimal('driver_fee_per_hour', 10, 2);
        $table->decimal('profit_margin', 5, 2);

        $table->timestamps();

        // Optional: unique constraint to avoid duplicate provider entries
        $table->unique(['provider_id'], 'unique_provider_extra_cost');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'bookings' => function ($table) {
        $table->increments('id');

        $table->unsignedInteger('user_id');
        $table->unsignedInteger('provider_id');
        $table->unsignedInteger('vehicle_id');

        $table->string('from_location');
        $table->string('destination');
        $table->unsignedInteger('distance');

        $table->dateTime('from_date');
        $table->dateTime('to_date');

        $table->integer('total_days')->default(1);

        $table->decimal('total_amount', 12, 2)->default(0); 

        $table->string('status')->default('pending');

        $table->timestamps();

        $table->index(['user_id', 'provider_id']);
        $table->index('vehicle_id');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },


    'vehicle_status' => function ($table) {
        $table->increments('id');
        $table->unsignedInteger('vehicle_id');
        $table->string('status'); // available, busy, maintenance…

        $table->timestamps();

        $table->unique('vehicle_id', 'unique_vehicle_status');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'drivers_working_history' => function ($table) {
        $table->increments('id');

        $table->unsignedInteger('driver_id');

        $table->string('status')->default('idle'); 
        $table->integer('total_trips')->default(0);

        $table->dateTime('last_trip_end')->nullable();
        $table->dateTime('last_assigned')->nullable();

        $table->timestamps();

        $table->unique('driver_id', 'unique_driver_history');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },

    'driver_trips' => function ($table) {
        $table->increments('id');

        $table->unsignedInteger('driver_id');
        $table->unsignedInteger('booking_id');

        $table->string('status')->default('assigned'); // assigned / started / ended…

        $table->dateTime('start_time')->nullable();
        $table->dateTime('end_time')->nullable();

        $table->timestamps();

        $table->index('driver_id');
        $table->index('booking_id');

        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
    },



];


// Tạo table
foreach ($tables as $name => $callback) {
    if (!Capsule::schema()->hasTable($name)) {
        Capsule::schema()->create($name, $callback);
        echo "Table '{$name}' created successfully.\n";
    } else {
        echo "Table '{$name}' already exists.\n";
    }
}
