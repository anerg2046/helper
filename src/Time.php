<?php

/**
 * 时间处理类
 * @author Coeus <r.anerg@gmail.com>
 */

namespace anerg\helper;

class Time
{

    public static function pretty($time)
    {
        $return = '';
        if (!is_numeric($time)) {
            $time = strtotime($time);
        }
        $htime = date('H:i', $time);
        $dif   = abs(time() - $time);
        if ($dif < 10) {
            $return = '刚刚';
        } else if ($dif < 3600) {
            $return = floor($dif / 60) . '分钟前';
        } else if ($dif < 10800) {
            $return = floor($dif / 3600) . '小时前';
        } else if (date('Y-m-d', $time) == date('Y-m-d')) {
            $return = '今天 ' . $htime;
        } else if (date('Y-m-d', $time) == date('Y-m-d', strtotime('-1 day'))) {
            $return = '昨天 ' . $htime;
        } else if (date('Y-m-d', $time) == date('Y-m-d', strtotime('-2 day'))) {
            $return = '前天 ' . $htime;
        } else if (date('Y', $time) == date('Y')) {
            $return = date('m-d H:i', $time);
        } else {
            $return = date('Y-m-d H:i', $time);
        }
        return $return;
    }

}
