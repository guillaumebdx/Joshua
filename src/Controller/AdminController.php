<?php

namespace App\Controller;

use App\Model\ContestManager;
use App\Model\CampusManager;
use App\Model\SpecialtyManager;
use App\Model\UserManager;
use App\Service\SpecialtyFormControl;
use App\Service\CampusFormControl;
use App\Service\ContestFormControl;

class AdminController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('admin/admin.html.twig');
    }

    // CHALLENGE

    // CONTEST

    public function manageContest()
    {
        $campuses     = new CampusManager('campus');
        $campusesList = $campuses->selectAll();

        $contests     = new ContestManager();
        $contestsList = $contests->selectAll();

        $contest      = null;

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['createBlankContest'])) {
            $contest = new ContestFormControl($_POST);
            $errors  = $contest->getErrors();
            if (count($errors) === 0) {
                $contestManager = new ContestManager();
                $contestManager->addContest($contest);
                header('Location: /admin/managecontest');
                exit;
            }
        }

        return $this->twig->render('admin/contest.html.twig', [
            'campuses' => $campusesList,
            'contests' => $contestsList,
            'contest'  => $contest
        ]);
    }

    // USERS
    public function manageUsers()
    {
        $usersManager     = new UserManager();
        $users = $usersManager->selectAllOrderBy('lastname', 'ASC');

        return $this->twig->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }


    public function setUserAdmin()
    {
        $json         = file_get_contents('php://input');
        $data         = json_decode($json, true);
        $usersManager = new UserManager();
        $status       = ($data['is_admin']) ? 1 : 0;

        if ($data['is_admin']) {
            $texte = $data['username'] . ' est désormais administrateur';
            $usersManager->userSetAdmin($status, $data['user_id']);
        } else {
            $texte = $data['username'] . ' n\'est plus administrateur';
            $usersManager->userSetAdmin($status, $data['user_id']);
        }

        return $this->twig->render('/ajaxviews/toast_admin_user.html.twig', [
            'data' => $texte,
        ]);
    }

    public function setUserActif()
    {
        $json         = file_get_contents('php://input');
        $data         = json_decode($json, true);
        $usersManager = new UserManager();
        $status       = ($data['is_admin']) ? 1 : 0;

        if ($data['is_admin']) {
            $texte = $data['username'] . ' est désormais actif';
            $usersManager->userSetActive($status, $data['user_id']);
        } else {
            $texte = $data['username'] . ' est désormais inactif';
            $usersManager->userSetActive($status, $data['user_id']);
        }

        return $this->twig->render('/ajaxviews/toast_admin_user.html.twig', [
            'data' => $texte,
        ]);
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
        $campusManager   = new CampusManager('campus');
        $errors          = [];
        $campus          = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $campus = new CampusFormControl($_POST);
            $errors = $campus->getErrors();
            if (count($errors) === 0) {
                $campusManager->insertCampus($campus);
                header('Location: /admin/index');
            }
        }
        $result=[
            'errors' => $errors,
            'campus' => $campus,
        ];
        return $this->twig->render('admin/campus.html.twig', $result);
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
            'errors'     => $errors,
            'specialty'  => $specialty,
            'specialties'=> $specialties,

        ];
        return $this->twig->render('Admin/specialty.html.twig', $result);
    }
}
