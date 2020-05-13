<?php


namespace App\Service;

use App\Model\ChallengeManager;
use App\Model\ContestHasChallengeManager;
use App\Model\ContestManager;
use App\Model\UserHasContestManager;
use Exception;

class ContestService
{
    const NUMBER_OF_DIFFICULTIES = 5;

    /**
     * </p>Start contest with the first challenge.</p>
     * @param int $contest
     * @return array
     * @throws Exception
     */
    public function startFirstChallenge(int $contest): array
    {
        $challengesInContest = new ContestHasChallengeManager();
        $playerManager       = new UserHasContestManager();
        $challengeManager    = new ChallengeManager();
        $firstChallenge      = $challengesInContest->getNextChallengeToPlay(1, $contest);
        $playerManager->startNextChallenge($firstChallenge, $contest);
        return $challengeManager->challengeOnTheWayByUser($contest);
    }

    /**
     * @param int $contest
     * @return array
     */
    public function listChallengesWithSuccess(int $contest): array
    {
        $listOfChallenges = [];
        $challengeManager = new ChallengeManager();
        $challengesList   = $challengeManager->getChallengesByContest($contest);
        $playerManager    = new UserHasContestManager();
        foreach ($challengesList as $challenge) {
            $listOfChallenges[] = [
                'id'     => $challenge['challenge_id'],
                'name'   => $challenge['name'],
                'order'  => $challenge['order_challenge'],
                'status' => $playerManager->challengeStatus($challenge['challenge_id'], $contest),
                'time'   => $playerManager->challengeTimeToSucceedByUser($challenge['challenge_id'], $contest),
            ];
        }
        return $listOfChallenges;
    }

    /**
     * @param string $difficulty
     * @return string
     */
    public function difficulties(string $difficulty): string
    {
        $returnStars = '';
        $limit = 0;
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
            $returnStars .= '<i class="fa fa-star text-red"></i>';
        }
        for ($i=$limit; $i<self::NUMBER_OF_DIFFICULTIES; $i++) {
            $returnStars .= '<i class="fa fa-star text-white"></i>';
        }
        return '<small class="info-challenge-title">Difficulty level</small><br>' . $returnStars;
    }

    /**
     * @param int $contest
     * @return bool
     * @throws Exception
     */
    public static function isSolutionPossible(int $contest): bool
    {
        $return         = false;
        $contestManager = new ContestManager();
        $theContest     = $contestManager->selectOneById($contest);

        if ($theContest) {
            $endDate = ContestDate::getContestEndDate($theContest['started_on'], $theContest['duration']);

            if (!ContestDate::isEnded($endDate) &&
                !ContestService::isContestCompletedByUser($contest, $_SESSION['user_id'])) {
                $return = true;
            }
        }
        return $return;
    }

    /**
     * @param int $contest
     * @param int $user
     * @return bool
     * @throws Exception
     */
    public static function isContestCompletedByUser(int $contest, int $user): bool
    {
        $challengesInContest = new ContestHasChallengeManager();
        $playerManager = new UserHasContestManager();
        $challengeNumber = $challengesInContest->getNumberOfChallengesInContest($contest);
        $numberPlayed = $playerManager->getNumberFlagsPlayedByUserInContest($user, $contest);
        return $challengeNumber === $numberPlayed;
    }
}
