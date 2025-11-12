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
