<?php
require __DIR__ . '/../../bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;


// Danh sách table để tạo
$tables = [
    'users' => function($table) {
        $table->increments('id');            // Primary key, auto-increment
        $table->string('first_name');        // Họ
        $table->string('last_name');         // Tên
        $table->string('password');          // Mật khẩu
        $table->string('email')->unique();   // Email, duy nhất
        $table->string('phone_number')->nullable(); // Số điện thoại, có thể để null
        $table->string('portrait')->nullable();     // Ảnh đại diện, có thể để null
        $table->timestamps();      
    },

    'providers' => function($table) {
        $table->increments('id');                       // Primary key
        $table->string('name');                         // Tên nhà cung cấp
        $table->string('owner_name');                   // Tên người đại diện
        $table->string('portrait_owner');   // Ảnh đại diện của chủ
        $table->string('email')->unique();  // Email, có thể null
        $table->string('phone_number');     // Số điện thoại
        $table->string('address');          // Địa chỉ văn phòng
        $table->string('city');             // Thành phố
        $table->decimal('average_rates', 3, 2)->nullable(); // Đánh giá trung bình, 1–5 sao
        $table->boolean('is_active')->default(true);    // Nhà cung cấp có hoạt động không
        $table->boolean('is_banned')->default(false);   // Nhà cung cấp bị cấm không
        $table->text('reason_is_banned')->nullable();   // Lý do bị cấm
        $table->timestamps();                           // created_at, updated_at
    },

    'drivers' => function($table) {
        $table->increments('id');                      // Primary key
        $table->string('full_name');                   // Họ và tên
        $table->date('date_of_birth');                 // Ngày sinh
        $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Giới tính
        $table->string('CCCD')->unique();             // CCCD, duy nhất
        $table->string('phone_number');   // Số điện thoại
        $table->string('email')->unique()->nullable(); // Email, có thể null
        $table->string('license_number')->unique();   // Số GPLX, duy nhất
        $table->enum('license_class', ['A1','A2','B1','B2','C','D','E','F']); // Loại GPLX
        $table->date('license_issue_date');           // Ngày cấp GPLX
        $table->date('license_expiry_date');          // Ngày hết hạn GPLX
        $table->string('issued_by');                  // Cơ quan cấp GPLX
        $table->enum('license_status', ['valid','expired','suspended'])->default('valid'); // Tình trạng GPLX
        $table->enum('status', ['available','on_trip','unavailable'])->default('available'); // Tình trạng tài xế
        $table->decimal('average_rates', 3, 2)->nullable(); // Đánh giá trung bình, ví dụ 4.75
        $table->unsignedInteger('id_provider'); // Khóa tới nhà cung cấp (provider)
        $table->timestamps();

        // Foreign key (nếu có bảng providers)
        $table->foreign('id_provider')->references('id')->on('providers')->onDelete('cascade');
    },

    'vehicles' => function($table) {
        $table->increments('id');                        // Primary key
        $table->string('license_plate')->unique();       // Biển số xe
        $table->enum('type', ['car', 'truck', 'motorbike', 'bus'])->default('car'); // Loại xe
        $table->string('brand');                         // Thương hiệu
        $table->string('model');                         // Model xe
        $table->year('year_of_manufacture');             // Năm sản xuất
        $table->unsignedInteger('seat_count'); // Số chỗ (nếu là car/bus)
        $table->unsignedInteger('id_provider');          // Khóa ngoại tới provider
        $table->enum('status', ['available','rented','maintenance'])->default('available'); // Trạng thái
        $table->unsignedInteger('capacity'); // Dung tích xi-lanh (cc)
        $table->decimal('payload_capacity', 8, 2)->nullable(); // Tải trọng (kg hoặc tấn), chỉ áp dụng cho xe tải
        $table->enum('license_class', ['B1','B2','C','D','E','F']); // Loại GPLX cần lái xe
        $table->decimal('fuel_consumption', 5, 2)->nullable(); // Lượng nhiên liệu tiêu thụ (l/100km)
        $table->decimal('maintenance', 10, 2)->nullable();     // Phí bảo dưỡng
        $table->timestamps();

        // Foreign key tới provider
        $table->foreign('id_provider')->references('id')->on('providers')->onDelete('cascade');
    },

    'bookings' => function($table) {
        $table->increments('id');             // Primary key, auto-increment
        $table->unsignedInteger('id_user');   // Khóa ngoại tới users
        $table->string('from_location');      // Điểm đi
        $table->string('to_location');        // Điểm đến
        $table->string('distance');    // Khoảng cách (có thể để decimal)
        $table->date('from_date');            // Ngày đi
        $table->date('to_date');              // Ngày về
        $table->time('depature_time');        // Thời gian booking (hệ thống xử lý)
        $table->time('end_time')->nullable(); // Thời gian kết thúc chuyến
        $table->unsignedInteger('id_vehicle'); // Khóa tới vehicle
        $table->unsignedInteger('id_driver')->nullable(); // Khóa tới driver (có thể null nếu chưa assign)
        $table->integer('members')->default(1); // Số thành viên
        $table->string('pick_up_point'); // Điểm nhận xe
        $table->enum('status', ['pending', 'accepted'])->default('pending'); // Trạng thái
        $table->timestamps();                 // created_at, updated_at

        // Foreign key (nếu muốn)
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('id_driver')->references('id')->on('drivers')->onDelete('set null');
        $table->foreign('id_vehicle')->references('id')->on('vehicles')->onDelete('cascade');

    },

     'order_payment' => function($table) {
        $table->increments('id');                      // Primary key
        $table->unsignedInteger('id_booking');         // Khóa ngoại tới booking
        $table->decimal('paid_deposit', 10, 2)->default(0);   // Tiền đặt cọc đã thanh toán
        $table->decimal('remaining_amount', 10, 2)->default(0); // Số tiền còn lại
        $table->decimal('grand_total', 10, 2)->default(0);      // Tổng tiền
        $table->boolean('is_paid_completely')->default(false);  // Thanh toán xong hay chưa
        $table->timestamps();

        // Foreign key tới bookings
        $table->foreign('id_booking')->references('id')->on('bookings')->onDelete('cascade');
    },

    'draft_booking' => function($table) {
        $table->increments('id');             // Primary key
        $table->unsignedInteger('id_user');   // Khóa ngoại tới users
        $table->string('from_location');      // Điểm đi
        $table->string('to_location');        // Điểm đến
        $table->string('distance');    // Khoảng cách (km)
        $table->date('from_date');            // Ngày đi
        $table->date('to_date');              // Ngày về
        $table->time('depature_time');        // Thời gian đặt (hệ thống xử lý)
        $table->time('end_time'); // Thời gian kết thúc chuyến
        $table->unsignedInteger('id_vehicle')->nullable(); // Xe dự kiến
        $table->unsignedInteger('id_driver')->nullable();  // Tài xế dự kiến
        $table->integer('members')->default(1);           // Số lượng thành viên
        $table->string('pick_up_point')->nullable();      // Điểm nhận xe
        $table->timestamps();                             // created_at, updated_at

        // Foreign key
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('id_vehicle')->references('id')->on('vehicles')->onDelete('cascade');
        $table->foreign('id_driver')->references('id')->on('drivers')->onDelete('cascade');
    },

    'e_wallet' => function($table) {
        $table->increments('id');                     // Primary key
        $table->unsignedInteger('id_driver');        // Khóa ngoại tới driver
        $table->decimal('balance', 18, 2)->default(0); // Số dư, DECIMAL(18,2)
        $table->string('currency', 3)->default('VND'); // Loại tiền tệ, mặc định VND
        $table->timestamps();                         // created_at, updated_at

        // Foreign key tới driver
        $table->foreign('id_driver')->references('id')->on('drivers')->onDelete('cascade');
    },

    'bank_account' => function($table) {
        $table->increments('id');                     // Primary key
        $table->unsignedInteger('id_driver');        // Khóa ngoại tới driver
        $table->string('bank');                       // Tên ngân hàng
        $table->string('account_number')->unique();  // Số tài khoản, duy nhất
        $table->string('full_name_in_bank');         // Tên chủ tài khoản
        $table->timestamps();                         // created_at, updated_at

        // Foreign key tới driver
        $table->foreign('id_driver')->references('id')->on('drivers')->onDelete('cascade');
    },

    'rent_rate' => function($table) {
        $table->increments('id');                     // Primary key
        $table->unsignedInteger('id_vehicle');       // Khóa ngoại tới vehicle
        $table->decimal('amount', 18, 2);            // Giá thuê
        $table->string('unit')->default('day');      // Đơn vị tính (mặc định theo ngày)
        $table->timestamps();                         // created_at, updated_at

        // Foreign key tới vehicle
        $table->foreign('id_vehicle')->references('id')->on('vehicles')->onDelete('cascade');
    },

    'vehicle_img' => function($table) {
        $table->increments('id');                     // Primary key
        $table->unsignedInteger('id_vehicle');       // Khóa ngoại tới vehicle
        $table->string('url');                        // Đường dẫn lưu file (trong server)
        $table->string('public_url')->nullable();     // URL công khai để hiển thị
        $table->timestamps();                         // created_at, updated_at

        // Foreign key tới vehicle
        $table->foreign('id_vehicle')->references('id')->on('vehicles')->onDelete('cascade');
    },

    'rates_driver' => function($table) {
        $table->increments('id');                     // Primary key
        $table->unsignedInteger('id_driver');        // Khóa ngoại tới driver
        $table->unsignedInteger('id_user');          // Khóa ngoại tới user (người đánh giá)
        $table->unsignedTinyInteger('number_rates'); // Số sao (1–5)
        $table->unsignedInteger('id_booking');       // Khóa ngoại tới booking
        $table->text('content')->nullable();         // Nội dung đánh giá, có thể null
        $table->timestamps();                         // created_at, updated_at

        // Foreign keys
        $table->foreign('id_driver')->references('id')->on('drivers')->onDelete('cascade');
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('id_booking')->references('id')->on('bookings')->onDelete('cascade');
    },

    'provider_imgs' => function($table) {
        $table->increments('id');                      // Primary key
        $table->unsignedInteger('id_provider');        // Khóa ngoại tới provider
        $table->string('url');                          // Đường dẫn lưu file (trong server)
        $table->string('public_url');       // URL công khai để hiển thị
        $table->timestamps();                           // created_at, updated_at

        // Foreign key tới provider
        $table->foreign('id_provider')->references('id')->on('providers')->onDelete('cascade');
    },

    'driver_waiting_list' => function($table) {
        $table->increments('id');                      // Primary key
        $table->unsignedInteger('id_driver');          // Khóa ngoại tới driver
        $table->boolean('is_waiting')->default(true);  // true = đang chờ, false = đã duyệt
        $table->timestamps();                           // updated_at

        // Foreign key tới driver
        $table->foreign('id_driver')->references('id')->on('drivers')->onDelete('cascade');
    },
    'action_logs' => function($table) {
        $table->increments('id');                          // Primary key
        $table->unsignedInteger('id_user');                // Khóa ngoại tới user (có thể là admin, khách, tài xế)
        $table->string('action_type');                     // Loại hành động ('create_order', 'payment', ...)
        $table->string('action_target');                   // Vật bị tác động ('Order#123', 'Wallet', 'Product#456', ...)
        $table->string('ip_address');          // IP của người dùng khi thực hiện hành động
        $table->string('user_agent');          // Trình duyệt hoặc app client
        $table->enum('status', ['fail','success'])->default('success'); // Kết quả hành động
        $table->timestamps();                      

        // Foreign key tới users
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
    },
     'device' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('id_action_log');        // Khóa ngoại tới action_logs
        $table->string('name');                           // Tên thiết bị (PC, Laptop, Mobile, ...
        $table->timestamps();                             // updated_at

        // Foreign key tới action_logs
        $table->foreign('id_action_log')->references('id')->on('action_logs')->onDelete('cascade');
    },
   'provider_are_banned' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('id_provider');          // Khóa ngoại tới provider
        $table->text('reason');                           // Lý do bị cấm
        $table->timestamp('time_ended')->nullable();     // Thời điểm hết hạn cấm, có thể null nếu chưa hết hạn
        $table->string('time_text')->nullable();         // Thời hạn cấm dạng text (vd: "30 days")
        $table->boolean('is_banned')->default(false);     // true = đang bị cấm, false = đã hết cấm
        $table->timestamps();                             // updated_at

        // Foreign key tới providers
        $table->foreign('id_provider')->references('id')->on('providers')->onDelete('cascade');
    },
    'drivers_are_banned' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('id_driver');           // Khóa ngoại tới driver
        $table->boolean('is_banned')->default(false);    // true = đang bị cấm, false = đã hết cấm
        $table->text('reason');                           // Lý do bị cấm
        $table->timestamp('time_ended')->nullable();     // Thời điểm hết hạn cấm, có thể null nếu chưa xác định
        $table->string('time_text')->nullable();         // Thời hạn dạng text (Vĩnh viễn, tạm thời, 3 ngày, ...)
        $table->timestamps();                             // updated_at

        // Foreign key tới drivers
        $table->foreign('id_driver')->references('id')->on('drivers')->onDelete('cascade');
    },
     'users_are_banned' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('id_user');             // Khóa ngoại tới users
        $table->boolean('is_banned')->default(false);    // true = đang bị cấm, false = đã hết cấm
        $table->text('reason');                           // Lý do bị cấm    // Thời điểm bị cấm
        $table->timestamp('time_ended')->nullable();     // Thời điểm hết hạn cấm, nullable nếu chưa xác định
        $table->string('time_text')->nullable();         // Thời hạn dạng text (Vĩnh viễn, tạm thời, 3 ngày,...)
        $table->timestamps();                             // updated_at

        // Foreign key tới users
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
    },

    'address' => function($table) {
        $table->increments('id');                     // Primary key
        $table->string('name');                        // Tên địa chỉ (ví dụ: "Văn phòng Hà Nội")
        $table->text('description');      // Mô tả chi tiết địa chỉ, có thể null
        $table->decimal('average_rates', 3, 2)->nullable(); // Đánh giá trung bình (1–5 sao), có thể null
        $table->timestamps();                          // created_at, updated_at
    },
     'address_imgs' => function($table) {
        $table->increments('id');                       // Primary key
        $table->string('name');                    // Tên ảnh hoặc mô tả ảnh - publicUrl
        $table->unsignedInteger('id_address');          // Khóa ngoại tới address
        $table->string('url');                           // Đường dẫn lưu file ảnh (trong server hoặc cloud)
        $table->timestamps();                            // created_at, updated_at

        // Foreign key tới address
        $table->foreign('id_address')->references('id')->on('address')->onDelete('cascade');
    },
    'address_short_videos' => function($table) {
        $table->increments('id');                        // Primary key
        $table->string('name');                           // Tên video hoặc mô tả ngắn (pulbicUrl)
        $table->unsignedInteger('id_address');           // Khóa ngoại tới address
        $table->string('url');                            // Đường dẫn lưu file video (trong server hoặc cloud)
        $table->timestamps();                             // created_at, updated_at

        // Foreign key tới address
        $table->foreign('id_address')->references('id')->on('address')->onDelete('cascade');
    },
    'rates_address' => function($table) {
        $table->increments('id');                     // Primary key
        $table->unsignedInteger('id_user');          // Khóa ngoại tới users (người đánh giá)
        $table->unsignedInteger('id_address');       // Khóa ngoại tới address
        $table->unsignedTinyInteger('number_rates'); // Số sao (1–5)
        $table->text('content')->nullable();         // Nội dung đánh giá, có thể null
        $table->timestamps();                         // created_at, updated_at

        // Foreign keys
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('id_address')->references('id')->on('address')->onDelete('cascade');
    },
      'rate_imgs_address' => function($table) {
        $table->increments('id');                       // Primary key
        $table->unsignedInteger('id_rate_address');     // Khóa ngoại tới rates_address
        $table->string('url');                           // Đường dẫn lưu file ảnh (trong server hoặc cloud)
        $table->string('public_url')->nullable();       // URL công khai để hiển thị
        $table->timestamps();                            // created_at, updated_at

        // Foreign key tới rates_address
        $table->foreign('id_rate_address')->references('id')->on('rates_address')->onDelete('cascade');
    },

    'rate_videos_address' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('id_rate_address');      // Khóa ngoại tới rates_address
        $table->string('url');                            // Đường dẫn lưu file video (trong server hoặc cloud)
        $table->string('public_url')->nullable();        // URL công khai để hiển thị
        $table->timestamps();                             // created_at, updated_at

        // Foreign key tới rates_address
        $table->foreign('id_rate_address')->references('id')->on('rates_address')->onDelete('cascade');
    },
    'travel_spots' => function($table) {
        $table->increments('id');                     // Primary key
        $table->string('name');                        // Tên điểm tham quan
        $table->text('description')->nullable();      // Mô tả chi tiết
        $table->unsignedInteger('id_address');        // Khóa ngoại tới address
        $table->time('open_time')->nullable();        // Giờ mở cửa
        $table->time('close_time')->nullable();       // Giờ đóng cửa
        $table->decimal('price_tag', 18, 2)->nullable(); // Giá vé tham khảo
        $table->timestamps();                          // created_at, updated_at

        // Foreign key tới address
        $table->foreign('id_address')->references('id')->on('address')->onDelete('cascade');
    },

    'travel_spot_imgs' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('id_travel_spot');       // Khóa ngoại tới travel_spots
        $table->string('url');                            // Đường dẫn lưu file ảnh (trong server hoặc cloud)
        $table->string('public_url')->nullable();        // URL công khai để hiển thị
        $table->timestamps();                             // created_at, updated_at

        // Foreign key tới travel_spots
        $table->foreign('id_travel_spot')->references('id')->on('travel_spots')->onDelete('cascade');
    },
    'travel_spot_videos' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('id_travel_spot');       // Khóa ngoại tới travel_spots
        $table->string('url');                            // Đường dẫn lưu file video (trong server hoặc cloud)
        $table->string('public_url')->nullable();        // URL công khai để hiển thị
        $table->timestamps();                             // created_at, updated_at

        // Foreign key tới travel_spots
        $table->foreign('id_travel_spot')->references('id')->on('travel_spots')->onDelete('cascade');
    },
    'rate_travel_spot' => function($table) {
        $table->increments('id');                     // Primary key
        $table->unsignedInteger('id_travel_spot');    // Khóa ngoại tới travel_spots
        $table->unsignedInteger('id_user');           // Khóa ngoại tới users (người đánh giá)
        $table->unsignedTinyInteger('number_rates');  // Số sao (1–5)
        $table->text('content')->nullable();          // Nội dung đánh giá, có thể null
        $table->timestamps();                          // created_at, updated_at

        // Foreign keys
        $table->foreign('id_travel_spot')->references('id')->on('travel_spots')->onDelete('cascade');
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
    },
    'rate_imgs_travel_spots' => function($table) {
        $table->increments('id');                         // Primary key
        $table->unsignedInteger('id_rate_travel_spot');   // Khóa ngoại tới rate_travel_spot
        $table->string('url');                             // Đường dẫn lưu file ảnh (trong server hoặc cloud)
        $table->string('public_url')->nullable();         // URL công khai để hiển thị
        $table->timestamps();                              // created_at, updated_at

        // Foreign key tới rate_travel_spot
        $table->foreign('id_rate_travel_spot')->references('id')->on('rate_travel_spot')->onDelete('cascade');
    },
    'rate_videos_travel_spots' => function($table) {
        $table->increments('id');                         // Primary key
        $table->unsignedInteger('id_rate_travel_spot');   // Khóa ngoại tới rate_travel_spot
        $table->string('url');                             // Đường dẫn lưu file video (trong server hoặc cloud)
        $table->string('public_url')->nullable();         // URL công khai để hiển thị
        $table->timestamps();                              // created_at, updated_at

        // Foreign key tới rate_travel_spot
        $table->foreign('id_rate_travel_spot')->references('id')->on('rate_travel_spot')->onDelete('cascade');
    },
    'historical_bookings' => function($table) {
        $table->increments('id');                         // Primary key
        $table->unsignedInteger('user_id');               // Khóa ngoại tới users
        $table->string('from_location');                  // Điểm xuất phát
        $table->string('to_location');                    // Điểm đến
        $table->string('distance');              // Quãng đường (km)
        $table->date('from_date');                        // Ngày bắt đầu
        $table->date('to_date');                          // Ngày kết thúc
        $table->time('departure_time');                   // Thời gian khởi hành
        $table->time('end_time');                         // Thời gian kết thúc chuyến
        $table->unsignedInteger('id_vehicle');           // Khóa ngoại tới vehicles
        $table->unsignedInteger('id_driver')->nullable(); // Khóa ngoại tới drivers (nullable nếu chưa gán)
        $table->unsignedInteger('members')->nullable();   // Số lượng thành viên
        $table->string('pick_up_point')->nullable();      // Điểm nhận xe
        $table->boolean('is_canceled')->default(false);   // Trạng thái hủy chuyến
        $table->timestamps();                             // created_at, updated_at

        // Foreign keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('id_vehicle')->references('id')->on('vehicles')->onDelete('cascade');
        $table->foreign('id_driver')->references('id')->on('drivers')->onDelete('set null');
    },

    'historical_orders_payment' => function($table) {
            $table->increments('id');                         // Primary key
            $table->unsignedInteger('id_historical_booking'); // Khóa ngoại tới historical_bookings
            $table->boolean('is_deposited')->default(false);  // true nếu đã đặt cọc
            $table->decimal('paid_deposit', 18, 2)->nullable(); // Số tiền đã đặt cọc
            $table->decimal('remaining_amount', 18, 2)->nullable(); // Số tiền còn lại, có thể null
            $table->enum('status_order', ['pending','accepted','rejected'])->default('pending'); // Trạng thái đơn hàng (provider duyệt hay chưa)
            $table->timestamps();                             // created_at, updated_at

            // Foreign key tới historical_bookings
            $table->foreign('id_historical_booking')->references('id')->on('historical_bookings')->onDelete('cascade');
    },

    'country' => function($table) {
        $table->increments('id');           // Primary key
        $table->string('name');             // Tên quốc gia
        $table->string('code');         // Mã quốc gia (VD: "VN")
        $table->timestamps();               // created_at, updated_at
    },

    'province_city' => function($table) {
        $table->increments('id');                     // Primary key
        $table->unsignedInteger('id_country');        // Khóa ngoại tới country
        $table->string('name');                        // Tên tỉnh/thành phố
        $table->enum('type', ['province','city']);    // Loại: province hoặc city
        $table->string('code');                    // Mã tỉnh/thành phố, ví dụ "01" cho Hà Nội
        $table->timestamps();                          // created_at, updated_at

        // Foreign key tới country
        $table->foreign('id_country')->references('id')->on('country')->onDelete('cascade');
    },

    'districts' => function($table) {
        $table->increments('id');                       // Primary key
        $table->unsignedInteger('id_province');         // Khóa ngoại tới province_city
        $table->string('name');                          // Tên quận/huyện/thị xã/thành phố
        $table->enum('type', ['district','town','city']); // Loại hành chính
        $table->string('code');                      // Mã hành chính, ví dụ "760" cho Q.1
        $table->timestamps();                            // created_at, updated_at

        // Foreign key tới province_city
        $table->foreign('id_province')->references('id')->on('province_city')->onDelete('cascade');
    },
    'ward_commune' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('id_district');          // Khóa ngoại tới districts
        $table->string('name');                           // Tên phường/xã/thị trấn
        $table->enum('type', ['ward','commune','townlet']); // Loại hành chính
        $table->string('code');                       // Mã hành chính, ví dụ "26734" cho P. Bến Nghé
        $table->timestamps();                             // created_at, updated_at

        // Foreign key tới districts
        $table->foreign('id_district')->references('id')->on('districts')->onDelete('cascade');
    },
     'address' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('user_id');             // Khóa ngoại tới users
        $table->unsignedInteger('ward_id');             // Khóa ngoại tới ward_commune
        $table->string('street_address');               // Tên đường, số nhà
        $table->text('full_address');                   // Địa chỉ đầy đủ (street + ward + district + province + country)
        $table->timestamps();                           // created_at, updated_at

        // Foreign keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('ward_id')->references('id')->on('ward_commune')->onDelete('cascade');
    },
     'complaints' => function($table) {
        $table->increments('id');                        // Primary key
        $table->unsignedInteger('id_user');             // Khóa ngoại tới users (người khiếu nại)
        $table->unsignedInteger('id_booking');          // Khóa ngoại tới bookings (hoặc historical_bookings nếu là chuyến hoàn tất)
        $table->text('reason');                          // Lý do khiếu nại
        $table->text('solution')->nullable();           // Giải pháp đã xử lý (có thể null nếu chưa xử lý)
        $table->boolean('is_resolved')->default(false); // Trạng thái đã được giải quyết hay chưa
        $table->timestamps();                            // created_at, updated_at

        // Foreign keys
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('id_booking')->references('id')->on('bookings')->onDelete('cascade');
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
