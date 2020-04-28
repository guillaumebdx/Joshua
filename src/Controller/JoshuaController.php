<?php

namespace App\Controller;

use App\Model\ContestManager;
use App\Model\UserManager;
use App\Service\ContestDate;
use App\Service\IndexFormControl;
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
            $login       = $connectUser->getProperty('email');
            $password    = $connectUser->getProperty('password');
            if (count($connectUser->getErrors()) === 0) {
                $userManager = new UserManager();
                $user        = $userManager->selectOneByEmail($login);
                if ($user) {
                    if ($login === $user['email']) {
                        if (password_verify($password, $user['password'])) {
                            UserConnection::openConnection($user['id']);
                            header('Location: joshua/home');
                        } else {
                            $error = 'Invalid password !';
                        }
                    }
                } else {
                    $error = 'This email doesn\'t exist !';
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
        $contestManager  = new ContestManager();
        $visibleContests = $contestManager->getVisibleContests();

        $nbContests = count($visibleContests);
        for ($i=0; $i<$nbContests; $i++) {
            $visibleContests[$i]['active'] = (bool)$visibleContests[$i]['active'];

            $beginning = $visibleContests[$i]['beginning'];
            $duration  = $visibleContests[$i]['duration'];

            $visibleContests[$i]['formatted_duration']  = ContestDate::getDurationInHoursAndMinutes($duration);
            $visibleContests[$i]['end_date']            = ContestDate::getContestEndDate($beginning, $duration);
        }

        return $this->twig->render('Home/home.html.twig', ['contests' => $visibleContests]);
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
