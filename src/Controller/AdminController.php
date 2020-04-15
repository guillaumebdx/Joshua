<?php

namespace App\Controller;

use App\Model\ContestManager;

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

    // LANGUAGES
}
