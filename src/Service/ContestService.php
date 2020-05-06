<?php


namespace App\Service;

use App\Model\ChallengeManager;
use App\Model\ContestHasChallengeManager;
use App\Model\ContestManager;

class ContestService
{
    const NUMBER_OF_DIFFICULTIES = 5;

    public function listChallengesWithSuccess(int $contest) :array
    {
        $listOfChallenges=[];
        $challengeManager = new ChallengeManager();
        $challengesList = $challengeManager->getChallengesByContest($contest);
        foreach ($challengesList as $challenge) {
            $listOfChallenges[] =[
                'id' => $challenge['challenge_id'],
                'name' => $challenge['name'],
                'order' => $challenge['order_challenge'],
                'status' => $challengeManager->challengeStatus($challenge['challenge_id'], $contest),
                'time' => $challengeManager->challengeTimeToSucceedByUser($challenge['challenge_id'], $contest),
            ];
        }
        return $listOfChallenges;
    }

    public function testChallengeSolution(int $challenge, string $solution) :bool
    {
        $challengeManager = new ChallengeManager();
        $challenge = $challengeManager->selectOneById($challenge);
        $return = ($solution === $challenge['flag']) ? true : false;
        return $return;
    }

    public function difficulties(string $difficulty) : string
    {
        $returnStars ='';
        $limit=0;
        switch ($difficulty) {
            case 'Easy':
                $limit=1;
                break;
            case 'Medium':
                $limit=2;
                break;
            case 'Hard':
                $limit=3;
                break;
            case 'Pro':
                $limit=4;
                break;
            case 'Nightmare':
                $limit=5;
                break;
            default:
                break;
        }
        for ($i=0; $i<$limit; $i++) {
            $returnStars.='<i class="fa fa-star text-red"></i>';
        }
        for ($i=$limit; $i<self::NUMBER_OF_DIFFICULTIES; $i++) {
            $returnStars.='<i class="fa fa-star text-white"></i>';
        }
        return '<small class="info-challenge-title">Difficulty level</small><br>' . $returnStars;
    }

    public static function isSolutionPossible(int $contest)
    {
        $return = false;
        $contestManager = new ContestManager();
        $theContest = $contestManager->selectOneById($contest);
        if ($theContest) {
            $endDate = ContestDate::getContestEndDate($theContest['started_on'], $theContest['duration']);
            if (!ContestDate::isEnded($endDate)) {
                $return = true;
            }
        }
        return $return;
    }
}
