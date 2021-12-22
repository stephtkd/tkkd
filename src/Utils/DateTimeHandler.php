<?php


namespace App\Utils;


class DateTimeHandler
{
    public static function ageCalculation($birthdate): string
    {
        $currentDate = date("Y-m-d");
        $interval = date_diff(date_create($birthdate), date_create($currentDate));
        return $interval->format("%y");
    }
}