<?php

namespace App\Helpers;

class Helper
{
    /**
     * Get greeting based on current time
     *
     * @return string
     */
    public static function greeting()
    {
        $hour = \Carbon\Carbon::now()->translatedFormat('H');

        if ($hour >= 3 && $hour < 11) {
            return 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            return 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }

    /**
     * Format date to Indonesian format
     *
     * @param string $date
     * @param bool $withTime
     * @return string
     */
    public static function formatDate($date, $withTime = false)
    {
        $format = $withTime ? 'd F Y H:i' : 'd F Y';
        return \Carbon\Carbon::parse($date)->translatedFormat($format);
    }

    /**
     * Format currency to Rupiah
     *
     * @param float $number
     * @return string
     */
    public static function formatRupiah($number)
    {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }

    /**
     * Limit string with dots
     *
     * @param string $string
     * @param int $limit
     * @return string
     */
    public static function limitString($string, $limit = 100)
    {
        if (strlen($string) > $limit) {
            return substr($string, 0, $limit) . '...';
        }
        return $string;
    }

    /**
     * Get current time info for debugging
     *
     * @return array
     */
    public static function getTimeDebug()
    {
        return [
            'current_time' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'timezone' => config('app.timezone'),
            'carbon_tz' => \Carbon\Carbon::now()->timezone->getName(),
            'php_tz' => date_default_timezone_get(),
        ];
    }

    /**
     * Get first name from full name
     *
     * @param string $fullName
     * @return string
     */
    public static function getFirstName($fullName)
    {
        $nameParts = explode(' ', trim($fullName));
        return $nameParts[0] ?? $fullName;
    }
}
