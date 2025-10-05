<?php

// src/Application/Services/LocationService.php
namespace App\Application\Service;

use App\Application\Port\Outbound\LocationApiPort;
use App\Adapter\Outbound\RedisCacheAdapter;

class LocationService {
    private RedisCacheAdapter $cache;
    private LocationApiPort $api;

    public function __construct(LocationApiPort $api, RedisCacheAdapter $cache) {
        $this->api = $api;
        $this->cache = $cache;
    }

    public function listProvinces(): array {
        $cacheKey = 'provinces_list';

        // Kiểm tra Redis cache
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return json_decode($cached, true);
        }

        // Nếu không có cache, gọi API
        $data = $this->api->getProvinces();

        // Lưu vào Redis cache
        $this->cache->set($cacheKey, json_encode($data), 3600); // TTL 1h

        return $data;
    }

    public function getProvincesWithWards(): array
    {
            $cacheKey = 'provinces_with_wards_list';

            // Kiểm tra Redis cache
            $cached = $this->cache->get($cacheKey);
            if ($cached !== null) {
                return json_decode($cached, true);
            }

            // Nếu không có cache, gọi API
            $provinces = $this->api->getProvinces();
            
            
            foreach ($provinces as &$province) {
                $province['wards'] = $this->api->getWardsByProvince($province['code']);
            }
            
                // Lưu vào Redis cache
                $this->cache->set($cacheKey, json_encode($provinces), 3600); // TTL 1h

        return $provinces;
    }

    public function createProvince($body, $imgs): array {
        $a =$body;
        $b = $imgs;
        return [];
    }
}
