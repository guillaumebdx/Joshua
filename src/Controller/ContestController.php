<?php


namespace App\Controller;

use App\Model\ChallengeManager;
use App\Model\ContestManager;
use App\Model\UserManager;
use App\Service\ContestDate;
use App\Service\ContestService;
use App\Service\Ranking;

class ContestController extends AbstractController
{
    public function play(int $contest)
    {
        // TODO AJOUTER SI CONTEST EXIST et si contest Contest is active et si pas terminé

        if (isset($_SESSION['user_id']) && $_SESSION['user_id']!='') {
            $contestService=new ContestService();
            $contestManager = new ContestManager();
            $userManager = new UserManager();
            $challengeManager = new ChallengeManager();

            $theContest = $contestManager->selectOneById($contest);

            if ($theContest) {
                $endDate = ContestDate::getContestEndDate($theContest['started_on'], $theContest['duration']);
                if (!empty($endDate)) {
                    $user = $userManager->selectOneById($_SESSION['user_id']);
                    $github = $user['github'];

                    $challengesList = $contestService->listChallengesWithSuccess($contest);
                    $challengeOnTheWay = $challengeManager->challengeOnTheWayByUser($contest);
                    $difficulty = $contestService->difficulties($challengeOnTheWay['difficulty']);

                    if ($challengeOnTheWay && !ContestDate::isEnded($endDate)) {
                        $ended = false;
                        $opened = true;
                    } else {
                        $ended = true;
                        $opened = false;
                    }

                    return $this->twig->render('Contests/play.html.twig', [
                        'contest' => $theContest,
                        'github' => $github,
                        'challenges' => $challengesList,
                        'challengeOnTheWay' => $challengeOnTheWay,
                        'difficulty' => $difficulty,
                        'ended' => $ended,
                        'open' => $opened,
                        'end_date' => $endDate,
                        'rank_users' => Ranking::getRankingContest($contest),
                    ]);
                }
            }
        } else {
            header('Location:/');
            die();
        }
    }

    public function results(int $contest)
    {
        $contestManager=new ContestManager();
        $theContest = $contestManager->selectOneById($contest);
        if ($theContest && !is_null($theContest['started_on'])) {
            $endDate = ContestDate::getContestEndDate($theContest['started_on'], $theContest['duration']);

            return $this->twig->render('Contests/results.html.twig', [
                'contest' => $theContest,
                'ended' => ContestDate::isEnded($endDate),
                'end_date' => $endDate,
            ]);
        } else {
            header('Location:/joshua/page404');
            die();
        }
    }

    public function sendSolution()
    {
        $data = file_get_contents('php://input');
        $json = json_decode($data);
        if (ContestService::isSolutionPossible($json->contest_id)) {
            $challengeManager = new ChallengeManager();
            $solutionUsed = $json->flagSolution;
            $challengeSolution = $challengeManager->getChallengeSolution($json->challenge_id);
            $return = [];
            if ($solutionUsed === $challengeSolution) {
                $challengeManager->registerChallengeSuccess($json->challenge_id, $json->contest_id);
                $nextFlagOrder = $json->challenge_id + 1;
                $nextChallenge = $challengeManager->getNextChallengeToPlay($nextFlagOrder, $json->contest_id);
                if ($nextChallenge) {
                    $challengeManager->startNextChallenge($nextChallenge, $json->contest_id);
                    $return['message'] = 'success';
                } else {
                    $return['message'] = 'end';
                }
            } else {
                $return['message'] = 'error';
            }
            return json_encode($return);
        } else {
            header('Location:/');
            die();
        }
    }

    public function getRankingInContest(int $contestId)
    {
        $contestManager = new ContestManager();
        $contest = $contestManager->selectOneById($contestId);
        if ($contest) {
            return $this->twig->render('Components/_ranking.html.twig', [
                'rank_users' => Ranking::getRankingContest($contestId),
            ]);
        }
    }
}
