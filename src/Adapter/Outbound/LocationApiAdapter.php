<?php
namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\LocationApiPort;

class LocationApiAdapter implements LocationApiPort {
    private string $baseUrl = "https://provinces.open-api.vn/api/v2/p/";

   public function getProvinces(): array {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: PHP']);

    $res = curl_exec($ch);

    // kiểm tra lỗi cURL **trước khi close**
    if ($res === false) {
        echo "cURL Error: " . curl_error($ch);
    }

    // đóng cURL
    curl_close($ch);

    // debug: in dữ liệu thô (JSON)
    echo "<pre>";
    var_dump($res);
    echo "</pre>";

    // decode JSON
    $data = json_decode($res, true);

    // debug: in dữ liệu mảng PHP
    echo "<pre>";
    print_r($data);
    echo "</pre>";

    return $data ?? [];
}
    

    // public function getDistricts(int $provinceId): array {
    //     $res = file_get_contents($this->baseUrl . "/districts?province_id=" . $provinceId);
    //     return json_decode($res, true) ?? [];
    // }
}
