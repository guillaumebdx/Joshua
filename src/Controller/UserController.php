<?php


namespace App\Controller;

use App\Model\CampusManager;
use App\Model\ContestHasChallengeManager;
use App\Model\SpecialtyManager;
use App\Model\UserManager;
use App\Service\Ranking;
use App\Service\UserFormControl;
use App\Service\UserEditFormControl;
use App\Service\UserConnection;

use App\Model\ContestManager;
use App\Service\UserService;

class UserController extends AbstractController
{
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
                header('location: /user/confirmuser/' . $idUser);
                die();
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
            header('location:/');
            die();
        }
    }

    public function confirmUser(int $idUser)
    {
        $user        = new UserManager();
        $userCreated = $user->selectOneById($idUser);

        return $this->twig->render('User/user_confirm.html.twig', [
            'user' => $userCreated,
        ]);
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'];
        $contestManager = new ContestManager();
        $userContests   = $contestManager->getContestsPlayedByUser($userId, 5);

        $contestHasChallengeManager = new ContestHasChallengeManager();
        $limit = count($userContests);
        for ($i = 0; $i < $limit; $i++) {
            $contestId = $userContests[$i]['id'];
            $ranking = Ranking::formatUserRankingInContest($contestId);
            $userContests[$i]['resume'] = [
                'started_on'           => $contestManager->getUserContestStartTime($userId, $contestId),
                'challenges_played'    => $ranking['flags_succeed'],
                'number_of_challenges' => $contestHasChallengeManager->getNumberOfChallengesInContest($contestId),
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
                header('location:/user/profile/' . $_SESSION['user_id']);
                die();
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
            header('location:/');
            die();
        }
    }

    public static function logOut()
    {
        $_SESSION = array();
        session_destroy();
        unset($_SESSION);
        header('location:/');
        die();
    }
}
