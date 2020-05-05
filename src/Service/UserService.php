<?php


namespace App\Service;

use App\Model\ContestManager;

class UserService
{
    public function formatUserRankingInContest(int $contest):array
    {
        $contestManager=new ContestManager();
        $palmares = $contestManager->getContestPalmares($contest);
        $suffix   = ['', 'st', 'nd', 'rd'];
        $userRank = array_search($_SESSION['user_id'], array_keys($palmares)) + 1;
        $rank     = ($userRank <= 3) ? $userRank . $suffix[$userRank] : $userRank . 'th';
        $medals   = ['', 'gold', 'silver', 'bronze'];
        return [
            'rank' => $rank,
            'medal' => $medals[$userRank],
            'flags_succeed' => $palmares[$_SESSION['user_id']]['flags_succeed'],
        ];
    }
}
