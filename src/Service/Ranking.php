<?php

namespace App\Service;

use App\Model\ContestManager;
use App\Model\UserManager;

class Ranking
{
    public static function getRankingContest(int $contest): array
    {
        $contestManager = new ContestManager();
        $ranking = $contestManager->getContestPalmares($contest);

        foreach ($ranking as $userId => $userRanking) {
            $userManager = new UserManager();
            $user = $userManager->selectOneById($userId);
            $ranking[$userId]['pseudo'] = $user['pseudo'];
            $ranking[$userId]['total_time'] = ContestDate::getDurationInHoursAndMinutes($userRanking['total_time']);
        }

        return $ranking;
    }
}
