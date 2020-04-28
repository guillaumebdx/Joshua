<?php

namespace App\Controller;

use App\Model\ContestManager;
use App\Model\CampusManager;
use App\Model\SpecialtyManager;
use App\Service\CampusFormControl;
use App\Service\SpecialtyFormControl;

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
        return $this->twig->render('Admin/contest.html.twig');
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
        $campusManager   = new CampusManager();
        $errors          = [];
        $campus          = null;
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
            'errors'=>$errors,
            'specialty'=>$specialty,
            'specialties'=>$specialties,
        ];
        return $this->twig->render('Admin/specialty.html.twig', $result);
    }
}
