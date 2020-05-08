<?php

namespace App\Controller;

use App\Model\ChallengeManager;
use App\Model\ContestManager;
use App\Model\CampusManager;
use App\Model\DifficultyManager;
use App\Model\SpecialtyManager;
use App\Model\TypeManager;
use App\Model\UserManager;
use App\Service\ChallengeFormControl;
use App\Service\SpecialtyFormControl;
use App\Service\CampusFormControl;
use App\Service\ContestFormControl;
use App\Service\TypeFormControl;

class AdminController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Admin/admin.html.twig');
    }

    // CHALLENGE

    public function manageChallenge()
    {
        $challenges     = new ChallengeManager();
        $challengesList = $challenges->selectAll();

        $difficulties     = new DifficultyManager();
        $difficultiesList = $difficulties->selectAll();

        $types     = new TypeManager();
        $typesList = $types->selectAll();

        $challenge = null;

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['createChallenge'])) {
            $challenge = new ChallengeFormControl($_POST);
            $errors  = $challenge->getErrors();
            if (count($errors) === 0) {
                $challengeManager = new ChallengeManager();
                $challengeManager->addChallenge($challenge);
                header('Location: /admin/managechallenge');
                die();
            }
        }

        return $this->twig->render('Admin/challenge.html.twig', [
            'challenges'   => $challengesList,
            'difficulties' => $difficultiesList,
            'types'        => $typesList,
            'challenge'    => $challenge,
        ]);
    }

    public function editChallenge($id)
    {
        $difficulties     = new DifficultyManager();
        $difficultiesList = $difficulties->selectAll();

        $types     = new TypeManager();
        $typesList = $types->selectAll();

        $challenge      = new ChallengeManager();
        $challengeEdit  = $challenge->selectOneById($id);

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['saveChallenge'])) {
            $challenge = new ChallengeFormControl($_POST);
            $errors  = $challenge->getErrors();

            if (count($errors) === 0) {
                $challengeManager = new ChallengeManager();
                $challengeManager->editChallenge($challenge, $id);
                header('Location: /admin/managechallenge');
                die();
            }
        }

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['deleteChallenge'])) {
            $challengeManager = new ChallengeManager();
            $challengeManager->deleteChallenge($id);
            header('Location: /admin/managechallenge');
            die();
        }

        return $this->twig->render('Admin/challenge_edit.html.twig', [
            'challenge'    => $challengeEdit,
            'difficulties' => $difficultiesList,
            'types'        => $typesList,
        ]);
    }

    // CONTEST

    public function manageContest()
    {
        $campuses     = new CampusManager();
        $campusesList = $campuses->selectAll();

        $contests     = new ContestManager();
        $contestsList = $contests->selectAll(ContestManager::NOT_ENDED);

        $contest      = null;

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['createBlankContest'])) {
            $contest = new ContestFormControl($_POST);
            $errors  = $contest->getErrors();
            if (count($errors) === 0) {
                $contestManager = new ContestManager();
                $contestManager->addContest($contest);
                header('Location: /admin/managecontest');
                die();
            }
        }

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['createFullContest'])) {
            $contest = new ContestFormControl($_POST);
            $errors  = $contest->getErrors();
            if (count($errors) === 0) {
                $contestManager = new ContestManager();
                $contestId = $contestManager->addContest($contest);
                header('Location: /admin/editcontest/' . $contestId);
                die();
            }
        }

        return $this->twig->render('Admin/contest.html.twig', [
            'campuses' => $campusesList,
            'contests' => $contestsList,
            'contest'  => $contest
        ]);
    }

    public function editContest($id)
    {
        $campuses     = new CampusManager();
        $campusesList = $campuses->selectAll();

        $challenges     = new ChallengeManager();
        $challengesList = $challenges->selectAll();

        $contest      = new ContestManager();
        $contestEdit  = $contest->selectOneById($id);

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['saveContest'])) {
            $contest = new ContestFormControl($_POST);
            $errors  = $contest->getErrors();

            if (count($errors) === 0) {
                $contestManager = new ContestManager();
                $contestManager->editContest($contest, $id);
                header('Location: /admin/managecontest');
                die();
            }
        }

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['deleteContest'])) {
            $contestManager = new ContestManager();
            $contestManager->deleteContest($id);
            header('Location: /admin/managecontest');
            die();
        }

        return $this->twig->render('Admin/contest_edit.html.twig', [
            'campuses'   => $campusesList,
            'challenges' => $challengesList,
            'contest'    => $contestEdit,
        ]);
    }

    public function setContestActive(string $contestId)
    {
        $contestManager = new ContestManager();
        $contestManager->setContestActive($contestId);
        header('Location: /admin/managecontest');
    }

    public function displayContest()
    {
        $json       = file_get_contents('php://input');
        $object     = json_decode($json);
        $contestId = $object->id;
        $isVisible = $object->visible;
        $contestManager = new ContestManager();

        if ($isVisible === 0) {
            $contestManager->displayContestOn($contestId);
        } elseif ($isVisible === 1) {
            $contestManager->displayContestOff($contestId);
        }
    }

    // USERS

    /**
     * Take an integer as input to manage pagination
     * @param int $page Default 1
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function manageUsers(int $page = 1)
    {
        $userManager     = new UserManager();
        $users = $userManager->selectAllOrderBy('lastname', 'ASC', $page, $_SESSION['user_id']);
        return $this->twig->render('Admin/users.html.twig', [
            'users'        => $users,
            'number_pages' => $userManager->numberOfPages(),
            'is_page'      => $page
        ]);
    }

    public function setUserAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $userManager = new UserManager();
            $status = ($data['status']) ? 1 : 0;
            $text = '';
            $isAdmin = ($_SESSION['is_admin'] === '1') ? true : false;

            if ($isAdmin) {
                if ($data['status']) {
                    $text = $data['username'] . ' is now admin';
                    $userManager->userSetAdmin($status, $data['user_id']);
                } else {
                    $text = $data['username'] . ' is not admin anymore';
                    $userManager->userSetAdmin($status, $data['user_id']);
                }
            } else {
                $text = 'You haven\'t got the good rights to do this';
            }
            return $this->twig->render('/Ajaxviews/toast_admin_user.html.twig', [
                'data' => $text,
            ]);
        }
    }

    public function setUserActive()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $userManager = new UserManager();
            $status = ($data['status']) ? 1 : 0;
            $text = '';
            $isAdmin = ($_SESSION['is_admin'] === '1') ? true : false;

            if ($isAdmin) {
                if ($data['status']) {
                    $text = $data['username'] . ' is now active';
                    $userManager->userSetActive($status, $data['user_id']);
                } else {
                    $text = $data['username'] . ' is not active anymore';
                    $userManager->userSetActive($status, $data['user_id']);
                }
            } else {
                $text = 'You haven\'t got the good rights to do this';
            }

            return $this->twig->render('/Ajaxviews/toast_admin_user.html.twig', [
                'data' => $text,
            ]);
        }
    }

    // CAMPUS

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function addCampus()
    {
        $campusManager   = new CampusManager();
        $errors          = [];
        $campus          = ('');
        $campus          = ucfirst(strtolower($campus));
        $campuses        = $campusManager->getAllCampusOrderBy('country', 'ASC', 'city', 'ASC');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $campus = new CampusFormControl($_POST);
            $errors = $campus->getErrors();
            if (count($errors) === 0) {
                $campusManager->insertCampus($campus);
                header('Location: /admin/addCampus');
            }
        }
        $result=[
            'errors'=>$errors,
            'campus'=>$campus,
            'campuses'=>$campuses,
        ];
        return $this->twig->render('Admin/campus.html.twig', $result);
    }

    // LANGUAGES

    public function addSpecialty()
    {
        $specialtyManager = new SpecialtyManager();
        $errors           = [];
        $specialty        = null;
        $specialties      = $specialtyManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $specialty = new SpecialtyFormControl($_POST);
            $errors    = $specialty->getErrors();
            if (count($errors) === 0) {
                $specialtyManager->insertSpecialty($specialty);
                header('Location: /admin/addSpecialty');
            }
        }
        $result=[
            'errors'      => $errors,
            'specialty'   => $specialty,
            'specialties' => $specialties,

        ];
        return $this->twig->render('Admin/specialty.html.twig', $result);
    }

    // TYPES

    public function addType()
    {
        $typeManager = new TypeManager();
        $errors      = [];
        $type        = null;
        $types       = $typeManager->selectAll();
        $typeExist  = 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type   = new TypeFormControl($_POST);
            $errors = $type->getErrors();
            if (count($errors) === 0) {
                if (!$typeManager->typeExists($type)) {
                    $typeManager->insertType($type);
                    $types       = $typeManager->selectAll();
                } else {
                    $typeExist = 1;
                }
            }
        }
        $result=[
            'errors' => $errors,
            'type'   => $type,
            'types'  => $types,
            'type_exist' => $typeExist,
        ];
        return $this->twig->render('Admin/type.html.twig', $result);
    }
}
