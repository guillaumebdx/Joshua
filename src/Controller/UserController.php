<?php


namespace App\Controller;

use App\Model\CampusManager;
use App\Model\ContestHasChallengeManager;
use App\Model\ContestManager;
use App\Model\SpecialtyManager;
use App\Model\UserManager;
use App\Service\Dispatch;
use App\Service\Ranking;
use App\Service\UserConnection;
use FormControl\UserEditFormControl;
use FormControl\UserFormControl;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserController extends AbstractController
{
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function register()
    {
        $campuses        = new CampusManager();
        $campusesList    = $campuses->selectAll();
        $specialties     = new SpecialtyManager();
        $specialtiesList = $specialties->selectAll();

        return $this->twig->render('User/register.html.twig', [
            'campuses'    => $campusesList,
            'specialties' => $specialtiesList,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function insertUser()
    {
        if (count($_POST) > 0 && isset($_POST['registerUser'])) {
            $check    = new UserFormControl($_POST);
            $formData = $check->getData();

            if (count($formData['errors']) === 0) {
                $newUser           = new UserManager();
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                // TODO SEND OBJECT FORM CONTROL
                $idUser            = $newUser->addUser($_POST);
                UserConnection::openConnection($idUser);
                Dispatch::toUrl('/user/confirmuser/' . $idUser);
            } else {
                $campuses        = new CampusManager();
                $campusesList    = $campuses->selectAll();
                $specialties     = new SpecialtyManager();
                $specialtiesList = $specialties->selectAll();

                return $this->twig->render('User/register.html.twig', [
                    'errors'      => $formData['errors'],
                    'user'        => $formData['user'],
                    'campuses'    => $campusesList,
                    'specialties' => $specialtiesList,
                ]);
            }
        } else {
            Dispatch::toUrl('/');
        }
    }

    /**
     * @param int $idUser
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function confirmUser(int $idUser)
    {
        $user        = new UserManager();
        $userCreated = $user->selectOneById($idUser);

        return $this->twig->render('User/user_confirm.html.twig', [
            'user' => $userCreated,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function profile()
    {
        $userId = $_SESSION['user_id'];
        $contestManager = new ContestManager();
        $userContests   = $contestManager->getContestsPlayedByUser($userId, 5);

        $challengesInContext = new ContestHasChallengeManager();
        $limit = count($userContests);
        for ($i = 0; $i < $limit; $i++) {
            $contestId = $userContests[$i]['id'];
            $ranking = Ranking::formatUserRankingInContest($contestId);
            $userContests[$i]['resume'] = [
                'started_on'           => $contestManager->getUserContestStartTime($userId, $contestId),
                'challenges_played'    => $ranking['flags_succeed'],
                'number_of_challenges' => $challengesInContext->getNumberOfChallengesInContest($contestId),
                'user_rank'            => $ranking['rank'],
                'medal'                => $ranking['medal'],
            ];
        }

        $user = new UserManager();
        $userCreated = $user->selectOneById($userId);

        return $this->twig->render('User/user_profile.html.twig', [
            'user'     => $userCreated,
            'contests' => $userContests,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function edit()
    {
        $user            = new UserManager();
        $userInfos       = $user->selectOneById($_SESSION['user_id']);
        $campuses        = new CampusManager();
        $campusesList    = $campuses->selectAll();
        $specialties     = new SpecialtyManager();
        $specialtiesList = $specialties->selectAll();

        return $this->twig->render('User/user_edit.html.twig', [
            'user'        => $userInfos,
            'campuses'    => $campusesList,
            'specialties' => $specialtiesList,
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editUser()
    {
        if (count($_POST) > 0 && isset($_POST['updateUser'])) {
            $check    = new UserEditFormControl($_POST);
            $formData = $check->getData();

            if (count($formData['errors']) === 0) {
                $userManager = new UserManager();
                if ($_POST['password'] != '') {
                    $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                }
                $userManager->updateUser($_POST);
                UserConnection::openConnection($_SESSION['user_id']);
                Dispatch::toUrl('/user/profile/' . $_SESSION['user_id']);
            } else {
                $campuses        = new CampusManager();
                $campusesList    = $campuses->selectAll();
                $specialties     = new SpecialtyManager();
                $specialtiesList = $specialties->selectAll();

                return $this->twig->render('User/user_edit.html.twig', [
                    'errors'      => $formData['errors'],
                    'campuses'    => $campusesList,
                    'specialties' => $specialtiesList,
                ]);
            }
        } else {
            Dispatch::toUrl('/');
        }
    }

    public static function logOut()
    {
        $_SESSION = array();
        session_destroy();
        unset($_SESSION);
        Dispatch::toUrl('/');
    }
}
