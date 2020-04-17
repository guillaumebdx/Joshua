<?php


namespace App\Controller;

use App\Model\CampusManager;
use App\Model\SpecialtyManager;
use App\Model\UserManager;
use App\Service\UserFormControl;

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
        if (count($_POST) > 0 && isset($_POST['registerUser'])) {
            $check = new UserFormControl($_POST);
            $formDatas = $check->getDatas();

            if (count($formDatas['errors']) === 0) {
                $newUser = new UserManager();
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $idUser = $newUser->addUser($_POST);
                header('location:/user/confirmuser/' . $idUser);
            } else {
                $campuses = new CampusManager('campus');
                $campusesList = $campuses->selectAll();
                $specialties = new SpecialtyManager('specialty');
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

    public function confirmUser($idUser)
    {
        $user = new UserManager();
        $userCreated = $user -> selectOneById($idUser);
        return $this->twig->render('User/user_confirm.html.twig', [
            'user' => $userCreated,
        ]);
    }
}
