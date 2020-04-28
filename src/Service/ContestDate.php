<?php

namespace App\Service;

use DateTime;

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
     * @return array
     */
    public static function getDurationInHoursAndMinutes(int $minutes): array
    {
        $date1 = new DateTime('00:00:00');
        $date2 = new DateTime('00:00:00');
        $date2->modify('+' . $minutes . ' minutes');
        $contestDuration = date_diff($date1, $date2);

        $duration['hours'] = $contestDuration->days * self::HOURS_IN_DAY + $contestDuration->h;
        $duration['minutes'] = $contestDuration->i;

        return $duration;
    }
}
