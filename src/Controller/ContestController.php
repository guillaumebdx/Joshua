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

            // TEST SOLUTION IF POST
            if (isset($_POST['solution']) && !empty($_POST['solution'])) {
                $contestService->testChallengeSolution(
                    $challengeManager->challengeOnTheWayByUser($contest),
                    $_POST['solution']
                );
            }

            //CONTEST
            $theContest = $contestManager->selectOneById($contest);
            if ($theContest) {
                $endDate = ContestDate::getContestEndDate($theContest['started_on'], $theContest['duration']);
                if (!empty($endDate)) {
                    // USER //
                    $user = $userManager->selectOneById($_SESSION['user_id']);
                    $github = $user['github'];

                    //CHALLENGES
                    $challengesList = $contestService->listChallengesWithSuccess($contest);
                    $challengeOnTheWay = $challengeManager->challengeOnTheWayByUser($contest);
                    if ($challengeOnTheWay && !ContestDate::isEnded($endDate)) {
                        $difficulty = $contestService->difficulties($challengeOnTheWay['difficulty']);

                        //RENDER
                        return $this->twig->render('Contests/play.html.twig', [
                            'contest' => $theContest,
                            'github' => $github,
                            'challenges' => $challengesList,
                            'challengeOnTheWay' => $challengeOnTheWay,
                            'difficulty' => $difficulty,
                            'ended' => false,
                            'open' => true,
                            'end_date' => $endDate,
                            'rank_users' => Ranking::getRankingContest($contest),
                        ]);
                    } else {
                        return $this->twig->render('Contests/play.html.twig', [
                            'contest' => $theContest,
                            'github' => $github,
                            'challenges' => $challengesList,
                            'ended' => true,
                            'end_date' => $endDate,
                            'rank_users' => Ranking::getRankingContest($contest),
                        ]);
                    }
                } else {
                    header('Location:/');
                    die;
                }
            } else {
                header('Location:/joshua/page404');
                die;
            }
        } else {
            header('Location:/');
            die;
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
            die;
        }
    }

    public function sendSolution()
    {
        $challengeManager=new ChallengeManager();
        $data = file_get_contents('php://input');
        $json = json_decode($data);
        $solutionUsed = $json->flagSolution;
        $challengeSolution = $challengeManager->getChallengeSolution($json->challenge_id);
        $return=[];
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
    }
}
