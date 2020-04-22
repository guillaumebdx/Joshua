<?php

namespace App\Controller;

use App\Model\ContestManager;
use App\Model\CampusManager;
use App\Service\CampusFormControl;
use App\Model\SpecialtyManager;

class AdminController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Admin/admin.html.twig');
    }

    // CHALLENGE

    // CONTEST

    public function createContest()
    {
        $campuses     = new CampusManager('campus');
        $campusesList = $campuses->selectAll();

        return $this->twig->render('Admin/contest.html.twig', [
            'campuses'    => $campusesList,
        ]);
    }

    public function insertContest()
    {
        return 'Hello world';
    }

    // USERS

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
            'errors'=>$errors,
            'campus'=>$campus,
        ];
        return $this->twig->render('Admin/campus.html.twig', $result);
    }

    // LANGUAGES
}
