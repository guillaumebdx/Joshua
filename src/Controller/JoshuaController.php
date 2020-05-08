<?php

namespace App\Controller;

use App\Model\ContestManager;
use App\Model\UserManager;
use App\Service\ContestDate;
use App\Service\IndexFormControl;
use App\Service\Ranking;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use App\Service\UserConnection;

class JoshuaController extends AbstractController
{
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index()
    {
        $login = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $connectUser = new IndexFormControl($_POST);
            $login = $connectUser->getProperty('pseudo');
            $password = $connectUser->getProperty('password');
            if (count($connectUser->getErrors()) === 0) {
                $userManager = new UserManager();
                $user = $userManager->selectOneByPseudo($login);
                if ($user) {
                    if ($login === $user['pseudo']) {
                        if (password_verify($password, $user['password'])) {
                            UserConnection::openConnection($user['id']);
                            header('Location: joshua/home');
                            exit();
                        } else {
                            $error = 'Invalid password !';
                        }
                    }
                } else {
                    $error = 'This pseudo doesn\'t exist !';
                }
            }
        }

        return $this->twig->render('Home/index.html.twig', [
            'login' => $login,
            'error' => $error,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function home()
    {
        $contestManager = new ContestManager();
        $visibleContests = $contestManager->selectAll(ContestManager::NOT_ENDED, true);

        $nbContests = count($visibleContests);
        for ($i = 0; $i < $nbContests; $i++) {
            $visibleContests[$i]['is_active'] = (bool)$visibleContests[$i]['is_active'];

            $beginning = $visibleContests[$i]['started_on'];
            $duration = $visibleContests[$i]['duration'];

            $visibleContests[$i]['formatted_duration'] = ContestDate::getDurationInHoursAndMinutes($duration, 1);
            $visibleContests[$i]['end_date'] = ContestDate::getContestEndDate($beginning, $duration);
        }

        return $this->twig->render('Home/home.html.twig', ['contests' => $visibleContests]);
    }

    public function oldContests()
    {
        $contestManager = new ContestManager();
        $oldContests = $contestManager->selectAll(ContestManager::ENDED);

        $nbContests = count($oldContests);
        for ($i = 0; $i < $nbContests; $i++) {
            $duration = $oldContests[$i]['duration'];
            $oldContests[$i]['formatted_duration'] = ContestDate::getDurationInHoursAndMinutes($duration, 1);
        }

        return $this->twig->render('Home/old_contests.html.twig', ['contests' => $oldContests]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function page404()
    {
        return $this->twig->render('404.html.twig', []);
    }
}
