<?php


namespace App\Controller;

use App\Model\ChallengeManager;
use App\Model\ContestManager;
use App\Model\UserManager;
use App\Service\ContestService;

class ContestController extends AbstractController
{
    public function play(int $contest)
    {
        // TODO AJOUTER SI CONTEST EXIST et si contest Contest is active et si pas terminé
        // TODO Peut être créer un contest status ??? active, on the way, ended

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
            //TODO Importer la date heure de fin du contest pour le timer

            // USER //
            $user = $userManager->selectOneById($_SESSION['user_id']);
            $github = $user['github'];

            //CHALLENGES
            $challengesList = $contestService->listChallengesWithSuccess($contest);
            $challengeOnTheWay = $challengeManager->challengeOnTheWayByUser($contest);
            if ($challengeOnTheWay) {
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
                ]);
            } else {
                return $this->twig->render('Contests/play.html.twig', [
                    'contest' => $theContest,
                    'github' => $github,
                    'challenges' => $challengesList,
                    'ended' => true,
                ]);
            }
        } else {
            header('Location:/');
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
