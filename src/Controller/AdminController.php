<?php

namespace App\Controller;

use App\Model\ContestManager;
use App\Model\CampusManager;
use App\Service\CampusFormControl;
use App\Service\ContestFormControl;
use App\Model\SpecialtyManager;

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

        return $this->twig->render('admin/contest.html.twig', [
            'campuses' => $campusesList,
            'contests' => $contestsList,
        ]);
    }

    public function insertContest()
    {
        $contestManager = new ContestManager();
        $contest        = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contest = new ContestFormControl($_POST);
            $errors = $contest->getErrors();
            if (count($errors) === 0) {
                $contestManager->addContest($contest);
                header('Location: /admin/managecontest');
            }
        }
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
        return $this->twig->render('admin/campus.html.twig', $result);
    }

    // LANGUAGES
}
