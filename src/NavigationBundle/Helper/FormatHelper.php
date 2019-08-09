<?php

namespace DH\NavigationBundle\Helper;

class FormatHelper
{
    /**
     * @param int $duration
     *
     * @return string
     */
    public static function formatTime(int $duration): string
    {
        static $timeFormats = [
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

        foreach ($timeFormats as $index => $format) {
            if ($duration >= $format[0]) {
                if ((isset($timeFormats[$index + 1]) && $duration < $timeFormats[$index + 1][0])
                    || $index === \count($timeFormats) - 1
                ) {
                    if (2 === \count($format)) {
                        return $format[1];
                    }

                    return floor($duration / $format[2]).' '.$format[1];
                }
            }
        }
    }

    /**
     * @param int $distance
     *
     * @return string
     */
    public static function formatDistance(int $distance): string
    {
        return $distance >= 1000 ? round($distance / 1000, 1).' km' : $distance.' m';
    }
}
