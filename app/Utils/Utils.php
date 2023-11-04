<?php

namespace App\Utils;


class Utils
{
    public static function getMonthArray() {

        $start_date = '2023-08-01';
        $end_date = '2023-10-01';

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

}
