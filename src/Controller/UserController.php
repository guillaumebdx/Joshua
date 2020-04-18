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

        $specialties = new SpecialtyManager();
        $specialtiesList = $specialties->selectAll();

        return $this->twig->render('User/register.html.twig', [
            'campuses' => $campusesList,
            'specialties' => $specialtiesList,
        ]);
    }

    public function insertUser()
    {
        if (count($_POST) > 0 && isset($_POST['registerUser'])) :
            $newUser = new UserManager();
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $idUser = $newUser->addUser($_POST);

            header('location:/user/confirmuser/' . $idUser);
        else :
            header('location:/home/index');
        endif;
    }

    public function confirmUser($idUser)
    {
        $user = new UserManager();
        $userCreated = $user -> selectOneById($idUser);
        $this->openConnection($idUser);
        return $this->twig->render('User/user_confirm.html.twig', [
            'user' => $userCreated,
        ]);
    }

    public function openConnection($idUser)
    {
        $user = new UserManager();
        session_start();
        $userConnected = $user -> selectOneById($idUser);
        $specialties = new SpecialtyManager();
        $userSpecialty = $specialties->selectOneById($userConnected['specialty_id']);
        $_SESSION['user_id'] = $idUser;
        $_SESSION['lastname'] = $userConnected['lastname'];
        $_SESSION['firstname'] = $userConnected['firstname'];
        $_SESSION['pseudo'] = $userConnected['pseudo'];
        $_SESSION['github'] = $userConnected['github'];
        $_SESSION['is_admin'] = $userConnected['is_admin'];
        $_SESSION['specialty'] = $userSpecialty['title'];
        $_SESSION['campus'] = $userConnected['campus_id'];
        $this->twig->addGlobal('session', $_SESSION);
    }
    public function logOut()
    {
        session_start();
        session_destroy();
        header('location:/');
    }
}
