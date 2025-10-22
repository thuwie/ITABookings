<?php

namespace App\Helper;

class FileHelper
{
    /**
     * Chuẩn hóa tên folder thành dạng không dấu, chỉ có chữ cái, số và gạch ngang.
     */
    public static function sanitizeFolderName(string $name): string
    {
        // 1️⃣ Loại bỏ dấu tiếng Việt
        $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);

        // 2️⃣ Chuyển về chữ thường
        $name = strtolower($name);

        // 3️⃣ Thay thế khoảng trắng và ký tự đặc biệt bằng dấu gạch ngang
        $name = preg_replace('/[^a-z0-9]+/', '-', $name);

        // 4️⃣ Loại bỏ dấu gạch ở đầu/cuối
        $name = trim($name, '-');

        return $name;
    }

    public static function formatCurrency(?float $amount): string
    {
        if ($amount === null) return '';
        return number_format($amount, 0, ',', '.') . ' ₫';
    }

    public static function formatTimeRange(?string $open, ?string $close): string
    {
        if (!$open || !$close) return '';

        $openFormatted  = date("g:i A", strtotime($open));
        $closeFormatted = date("g:i A", strtotime($close));

        return "{$openFormatted} - {$closeFormatted}";
    }
}
