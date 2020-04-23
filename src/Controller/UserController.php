<?php


namespace App\Controller;

use App\Model\CampusManager;
use App\Model\SpecialtyManager;
use App\Model\UserManager;
use App\Service\UserFormControl;
use App\Service\UserEditFormControl;

use App\Model\ContestManager;

class UserController extends AbstractController
{
    public function register()
    {
        $campuses        = new CampusManager('campus');
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
                $idUser            = $newUser->addUser($_POST);
                $this->openConnection($idUser);
                header('location: /user/confirmuser/' . $idUser);
            } else {
                $campuses        = new CampusManager('campus');
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
        }
    }

    public function confirmUser(int $idUser)
    {
        $user        = new UserManager();
        $userCreated = $user->selectOneById($idUser);

        return $this->twig->render('user/user_confirm.html.twig', [
            'user' => $userCreated,
        ]);
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'];

        $contestManager = new ContestManager();
        $userContests   = $contestManager->getContestsPlayedByUser($_SESSION['user_id'], 5);

        $limit = count($userContests);
        for ($i = 0; $i < $limit; $i++) {
            $contestId = $userContests[$i]['id'];

            $palmares = $contestManager->getContestPalmares($contestId);

            $userRank = array_search($userId, array_keys($palmares)) + 1;
            $suffix   = ['', 'st', 'nd', 'rd'];
            $medals   = ['', 'gold', 'silver', 'bronze'];
            $rank     = ($userRank <= 3) ? $userRank . $suffix[$userRank] : $userRank . 'th';

            $userContests[$i]['resume'] = [
                'started_on'           => $contestManager->getUserContestStartTime($userId, $contestId),
                'challenges_played'    => $palmares[$_SESSION['user_id']]['flags_succeed'],
                'number_of_challenges' => $contestManager->getNumberOfChallengesInContest($contestId),
                'user_rank'            => $rank,
                'medal'                => $medals[$userRank],
            ];
        }

        $user = new UserManager();
        $userCreated = $user->selectOneById($_SESSION['user_id']);

        return $this->twig->render('User/user_profile.html.twig', [
            'user'     => $userCreated,
            'contests' => $userContests,

        ]);
    }

    public function edit()
    {
        $user            = new UserManager();
        $userInfos       = $user->selectOneById($_SESSION['user_id']);
        $campuses        = new CampusManager('campus');
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
                $idUser = $userManager->updateUser($_POST);
                $this->openConnection($_SESSION['user_id']);
                header('location:/user/profile/' . $idUser);
            } else {
                $campuses        = new CampusManager('campus');
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
        }
    }

    public function openConnection($idUser)
    {
        $user = new UserManager();

        $userConnected = $user->selectOneById($idUser);
        $specialties   = new SpecialtyManager();
        $userSpecialty = $specialties->selectOneById($userConnected['specialty_id']);
        $campuses      = new CampusManager('campus');
        $userCampus    = $campuses->selectOneById($userConnected['campus_id']);

        $_SESSION['user_id']      = $idUser;
        $_SESSION['lastname']     = $userConnected['lastname'];
        $_SESSION['firstname']    = $userConnected['firstname'];
        $_SESSION['email']        = $userConnected['email'];
        $_SESSION['pseudo']       = $userConnected['pseudo'];
        $_SESSION['github']       = $userConnected['github'];
        $_SESSION['is_admin']     = $userConnected['is_admin'];
        $_SESSION['specialty']    = $userSpecialty['title'];
        $_SESSION['specialty_id'] = $userConnected['specialty_id'];
        $_SESSION['campus_id']    = $userConnected['campus_id'];
        $_SESSION['campus']       = $userCampus['city'];
    }

    public function logOut()
    {
        $_SESSION = array();
        session_destroy();
        unset($_SESSION);
        header('location:/');
    }
}
