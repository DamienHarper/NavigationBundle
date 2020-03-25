<?php

namespace DH\NavigationBundle\Helper;

class FormatHelper
{
    public static function formatTime(int $duration, int $precision = 1): string
    {
        static $formats = [
            [0, '< 1 sec'],
            [1, '1 sec'],
            [2, 'secs', 1],
            [60, '1 min'],
            [120, 'mins', 60],
            [3600, '1 hr'],
            [7200, 'hrs', 3600],
            [86400, '1 day'],
            [172800, 'days', 86400],
        ];

        foreach ($formats as $index => $format) {
            if ($duration >= $format[0]) {
                if ((isset($formats[$index + 1]) && $duration < $formats[$index + 1][0])
                    || $index === \count($formats) - 1
                ) {
                    if (2 === \count($format)) {
                        return $format[1];
                    }

                    --$precision;

                    $tmp = (int) floor($duration / $format[2]);
                    if (0 === $precision || 0 === $duration - ($tmp * $format[2])) {
                        return $tmp.' '.$format[1];
                    }

                    return $tmp.' '.$format[1].' '.self::formatTime($duration - ($tmp * $format[2]), $precision);
                }
            }
        }

        return '';
    }

    public static function formatDistance(int $distance, int $precision = 1): string
    {
        if ($distance < 1000) {
            return $distance.' m';
        }

        --$precision;

        $tmp = (int) floor($distance / 1000);
        if (0 === $precision || 0 === $distance - ($tmp * 1000)) {
            return round($distance / 1000, 1).' km';
        }

        return $tmp.' km '.self::formatDistance($distance - ($tmp * 1000), $precision);
    }
}
