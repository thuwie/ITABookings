<?php
namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\LocationApiPort;
use App\Domain\Entity\Province;

class LocationApiAdapter implements LocationApiPort {
    private string $baseUrl = "https://tinhthanhpho.com/api/v1/new-provinces";

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

    // decode JSON
    $decoded = json_decode($res, true); 
        return $decoded['data'] ?? [];  
    }
    

  public function getWardsByProvince(int $provinceCode): array {
        // Chuẩn hóa URL
        $url = rtrim($this->baseUrl, '/') . '/' .  $provinceCode . '/wards';

        // Gọi API
        $res = @file_get_contents($url);
        if ($res === false) {
            error_log("Failed to fetch wards from URL: $url");
            return [];
        }

        $decoded = json_decode($res, true);
        return $decoded['data'] ?? $decoded ?? [];
    }

    public function createProvince(Province $province, array $imgs){
        
    }

}
