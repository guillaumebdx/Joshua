<?php


namespace App\Controller;

use App\Model\CampusManager;
use App\Model\SpecialtyManager;
use App\Model\UserManager;

class UserController extends AbstractController
{
    public function register()
    {
        $campuses = new CampusManager('campus');
        $campusesList = $campuses->selectAll();

        $specialties = new SpecialtyManager('specialty');
        $specialtiesList = $specialties->selectAll();

        return $this->twig->render('User/register.html.twig', [
            'campuses' => $campusesList,
            'specialties' => $specialtiesList,
        ]);
    }

    public function insertUser()
    {
        if (count($_POST) > 0 && isset($_POST['registerUser'])) :
            $bdd = new UserManager();
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $bdd->addUser($_POST);
        endif;
    }
}
