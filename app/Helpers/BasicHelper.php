<?php

namespace App\Helpers;

class BasicHelper
{
    public static function dateForFileName(bool $withTimestamp = false, bool $withUnixId = false, bool $onlyTimestamp = false)
    {
        return implode('_', array_merge(
            [
                $onlyTimestamp ? time() : (windows_os() ? now()->format('d_m_y_H-i-s') : now()->format('d_m_y_H:i:s')),
            ],
            ($withTimestamp ? [time()] : []),
            ($withUnixId ? [uniqid()] : [])
        ));
        // return now()->format('d_m_y_H:i:s') . ($withTimestamp ? ('_' . time()) : '');
    }

    public static function getMonthName(int $monthNumber): string
    {
        return \Carbon\Carbon::now()->firstOfYear()->addMonths($monthNumber)->subMonth()->translatedFormat('F');
    }

    public static function getMoneyFormatIndo(int $number): string
    {
        return number_format($number, 2, ',', '.');
    }
}
