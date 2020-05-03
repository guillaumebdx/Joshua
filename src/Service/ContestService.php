<?php


namespace App\Service;

use App\Model\ChallengeManager;
use App\Model\ContestHasChallengeManager;

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
        if ($solution === $challenge['flag']) {
            return true;
        } else {
            return false;
        }
    }

    public function difficulties(string $difficulty) : string
    {
        $returnStars ='';
        $lim=0;
        switch ($difficulty) {
            case 'Easy':
                $lim=1;
                break;
            case 'Medium':
                $lim=2;
                break;
            case 'Hard':
                $lim=3;
                break;
            case 'Pro':
                $lim=4;
                break;
            case 'Nightmare':
                $lim=5;
                break;
            default:
                break;
        }
        for ($i=0; $i<$lim; $i++) {
            $returnStars.='<i class="fa fa-star text-red"></i>';
        }
        for ($i=$lim; $i<self::NUMBER_OF_DIFFICULTIES; $i++) {
            $returnStars.='<i class="fa fa-star text-white"></i>';
        }
        return '<small class="info-challenge-title">Difficulty level</small><br>' . $returnStars;
    }
}
