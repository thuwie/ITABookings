<?php

namespace App\Domain\ValueObject;

class MinuteHourConverter
{
    /**
     * Convert minutes to hours (decimal).
     * Example: 90 → 1.5
     */
    public static function minutesToHoursDecimal(int $minutes): float
    {
       return round($minutes / 60, 2);
    }

    /**
     * Convert minutes to hours and minutes (formatted).
     * Example: 90 → "1h 30m"
     */
    public static function minutesToHoursFormatted(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins  = $minutes % 60;

        return sprintf("%dh %02dm", $hours, $mins);
    }

    /**
     * Convert hours (decimal) to minutes.
     * Example: 1.5 → 90
     */
    public static function hoursToMinutes(float $hours): int
    {
        return (int) round($hours * 60);
    }

    /**
     * Convert "H:M" string to total minutes.
     * Example: "1:30" → 90
     */
    public static function hmToMinutes(string $hm): int
    {
        list($h, $m) = explode(':', $hm);
        return ((int)$h * 60) + (int)$m;
    }
}
