<?php

namespace App\Service;

use DateTime;
use DateTimeZone;
use Exception;

class ContestDate
{
    const HOURS_IN_DAY = 24;
    const STRING = 0;
    const ARRAY = 1;

    /**
     * @param string|null $startedOn
     * @param string $duration
     * @return string|null
     * @throws Exception
     */
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
     * @param array $contests
     * @return array
     * @throws Exception
     */
    public static function getContestsEndDateInArray(array $contests): array
    {
        foreach ($contests as $key => $contest) {
            $contests[$key]['end_date'] = self::getContestEndDate($contest['started_on'], $contest['duration']);
        }
        return $contests;
    }

    /**
     * @param int $minutes
     * @param int $format
     * Default string, if you want a array put ContestDate::ARRAY.
     * @return mixed
     */
    public static function getDurationInHoursAndMinutes(int $minutes, int $format = self::STRING)
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

        if ($format === self::ARRAY) {
            $result = $duration;
        }

        return $result;
    }

    /**
     * @param string $endDate
     * @return bool
     * @throws Exception
     */
    public static function isEnded(string $endDate): bool
    {
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        var_dump($now);
        $endDate = new DateTime($endDate);
        var_dump($endDate);
        die;
        return $endDate <= $now;
    }
}
