<?php

namespace App\Utils;


use App\Config\Constants;

class Utils
{
    public static function getMonthArray($start = null, $end = null) {

        $start_date = $start
            ? date('Y-m-d', $start)
            : Constants::START_DATE;

        $end_date = $end
            ? date('Y-m-d', $end)
            : date("Y-m-d", strtotime("-1 month",strtotime(date('Y-m-d'))));

        $arr_months = [
            '',
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        ];

        $start_timestamp = strtotime(str_replace('-', '/', $start_date));
        $end_timestamp = strtotime(str_replace('-', '/', $end_date));

        $start_month_timestamp = strtotime(date('F Y', $start_timestamp));
        $current_month_timestamp = strtotime(date('F Y', $end_timestamp));

        $arr_month = array();

        while( $current_month_timestamp >= $start_month_timestamp ) {
            $arr_month[strtolower(date('Y-m-t', $end_timestamp))] = $arr_months[date('n', $end_timestamp)] .' '. date('Y', $end_timestamp);
            $end_timestamp = strtotime('-1 month', $end_timestamp);

            $current_month_timestamp = strtotime(date('F Y', $end_timestamp));
        }

        return $arr_month;
    }

    public static function setFirstDay($date = null) {
        if (empty($date)) {
            return date('Y-m-d', $date);
        }

        $arr = explode("-", $date);
        $arr[2] = "01";
        return implode("-", $arr);
    }

    public static function setLastDay($date = null) {
        if (empty($date)) {
            return date("Y-m-t", strtotime(date("Y-m-t", $date)));
        }

        return date('Y-m-t', strtotime($date));
    }

    static function setMinusMonths($date = null, $minus = -1) {
        if (empty($date)) {
            return date('Y-m-d', strtotime(date('Y-m-d', $date) . " {$minus} months"));
        }

        return date('Y-m-d', strtotime($date . " {$minus} months"));
    }

    static function getPeriodMonthArray($start_date = null, $end_date = null, $months = 1)
    {
        $dates = [[
            "start_date" => $start_date,
            "end_date"   => $end_date
        ]];

        $end_date_point = strtotime(Constants::START_DATE);

        for ($i = 0; $i < $months; $i++) {
            $sd = Utils::setMinusMonths($dates[$i]["start_date"]);
            $ed = Utils::setLastDay($sd);

            if (strtotime($sd) < $end_date_point) {
                break;
            }

            $dates[] = [
                "start_date" => $sd,
                "end_date"   => $ed
            ];
        }

        return $dates;
    }
}
