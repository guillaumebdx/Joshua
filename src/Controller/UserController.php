<?php


namespace App\Controller;

use App\Model\CampusManager;
use App\Model\SpecialtyManager;
use App\Model\UserManager;
use App\Service\UserFormControl;
use App\Model\ContestManager;

class UserController extends AbstractController
{
    public function register()
    {
        $campuses = new CampusManager('campus');
        $campusesList = $campuses->selectAll();
        $specialties = new SpecialtyManager();
        $specialtiesList = $specialties->selectAll();

        return $this->twig->render('User/register.html.twig', [
            'campuses' => $campusesList,
            'specialties' => $specialtiesList,
        ]);
    }

    public function insertUser()
    {
        if (count($_POST) > 0 && isset($_POST['registerUser'])) {
            $check = new UserFormControl($_POST);
            $formDatas = $check->getDatas();

            if (count($formDatas['errors']) === 0) {
                $newUser = new UserManager();
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $idUser = $newUser->addUser($_POST);
                $this->openConnection($idUser);
                header('location:/user/confirmuser/' . $idUser);
            } else {
                $campuses = new CampusManager('campus');
                $campusesList = $campuses->selectAll();
                $specialties = new SpecialtyManager();
                $specialtiesList = $specialties->selectAll();

                return $this->twig->render('User/register.html.twig', [
                    'errors' => $formDatas['errors'],
                    'user' => $formDatas['user'],
                    'campuses' => $campusesList,
                    'specialties' => $specialtiesList,
                ]);
            }
        } else {
            header('location:/');
        }
    }

    public function confirmUser(int $idUser)
    {
            $user = new UserManager();
            $userCreated = $user->selectOneById($idUser);
            return $this->twig->render('User/user_confirm.html.twig', [
                'user' => $userCreated,
            ]);
    }

    public function profile()
    {
        //TODO Retirer la session de test dans UserController Methode profile
        /**
         * POUR TEST UNIQUEMENT - A RETIRER UNE FOIS LA CONNEXION USER ETABLIE
         */
        if (!isset($_SESSION['user_id'])) {
            $this->openConnection(5);
        }
        /**
         * END ****************************************************************
         */
        $contests = new ContestManager();
        $userContests = $contests->getContestsPlayedByUser($_SESSION['user_id'], 5);
        $userId = $_SESSION['user_id'];
        $limit = count($userContests);
        for ($i = 0; $i < $limit; $i++) {
            $contestId = $userContests[$i]['id'];
            $userContests[$i]['resume'] = [
                    'started_on'           => $contests->getUserContestStartTime($userId, $contestId),
                    'challenges_played'    => $contests->getNumberFlagsPlayedByUserInContest($userId, $contestId),
                    'number_of_challenges' => $contests->getNumberOfChallengesInContest($contestId),
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
        if (isset($_SESSION['user_id'])) {
            $user = new UserManager();
            $userInfos = $user->selectOneById($_SESSION['user_id']);
            $campuses = new CampusManager('campus');
            $campusesList = $campuses->selectAll();
            $specialties = new SpecialtyManager();
            $specialtiesList = $specialties->selectAll();

            return $this->twig->render('User/user_edit.html.twig', [
                'user' => $userInfos,
                'campuses' => $campusesList,
                'specialties' => $specialtiesList,
            ]);
        } else {
            header('location:/');
        }
    }

    public function openConnection($idUser)
    {
        $user = new UserManager();

        $userConnected = $user->selectOneById($idUser);
        $specialties = new SpecialtyManager();
        $userSpecialty = $specialties->selectOneById($userConnected['specialty_id']);
        $campuses = new CampusManager('campus');
        $userCampus = $campuses->selectOneById($userConnected['campus_id']);

        $_SESSION['user_id'] = $idUser;
        $_SESSION['lastname'] = $userConnected['lastname'];
        $_SESSION['firstname'] = $userConnected['firstname'];
        $_SESSION['email'] = $userConnected['email'];
        $_SESSION['pseudo'] = $userConnected['pseudo'];
        $_SESSION['github'] = $userConnected['github'];
        $_SESSION['is_admin'] = $userConnected['is_admin'];
        $_SESSION['specialty'] = $userSpecialty['title'];
        $_SESSION['specialty_id'] = $userConnected['specialty_id'];
        $_SESSION['campus_id'] = $userConnected['campus_id'];
        $_SESSION['campus'] = $userCampus['city'];
    }

    public function logOut()
    {

        session_destroy();
        header('location:/');
    }
}
