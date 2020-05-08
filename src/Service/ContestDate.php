<?php

namespace App\Service;

use DateTime;
use DateTimeZone;

class ContestDate
{
    const HOURS_IN_DAY = 24;

    public static function getContestEndDate(?string $startedOn, string $duration): ?string
    {
        if (isset($startedOn)) {
            $endDate = new DateTime($startedOn);
            $endDate->modify('+' . $duration . ' minutes');
            $result = $endDate->format('Y-m-d H:i:s');
        } else {
            $result = '';
        }
        return $result;
    }

    /**
     * @param int $minutes
     * Default string, if you want a array put 1;
     * @param int $format
     * @return mixed
     */
    public static function getDurationInHoursAndMinutes(int $minutes, int $format = 0)
    {
        $date1 = new DateTime('00:00:00');
        $date2 = new DateTime('00:00:00');
        $date2->modify('+' . $minutes . ' minutes');
        $contestDuration = date_diff($date1, $date2);

        $duration['hours'] = $contestDuration->days * self::HOURS_IN_DAY + $contestDuration->h;
        $duration['minutes'] = $contestDuration->i;

        if (strlen((string)$duration['minutes']) === 1) {
            $duration['minutes'] = 0 . $duration['minutes'];
        }

        if ($duration['hours'] === 0) {
            $result = $duration['minutes'] . 'min';
        } else {
            $result = $duration['hours'] . 'h' . $duration['minutes'] . 'm';
        }

        if ($format === 1) {
            $result = $duration;
        }

        return $result;
    }

    /**
     * @param string $endDate
     * @return bool
     */
    public static function isEnded(string $endDate): bool
    {
        $now = new DateTime(date('Y-m-d H:i:s'));
        $endDate = new DateTime($endDate);
        return ($endDate <= $now) ? true : false;
    }
}
