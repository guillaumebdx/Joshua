<?php


namespace App\Service;

use App\Controller\AbstractController;
use App\Model\UserManager;
use App\Model\CampusManager;
use App\Model\SpecialtyManager;

class UserConnection extends AbstractController
{
    public static function openConnection($idUser)
    {
        $user = new UserManager();

        $userConnected = $user->selectOneById($idUser);
        $specialties   = new SpecialtyManager();
        $userSpecialty = $specialties->selectOneById($userConnected['specialty_id']);
        $campuses      = new CampusManager();
        $userCampus    = $campuses->selectOneById($userConnected['campus_id']);

        $_SESSION['user_id']      = $idUser;
        $_SESSION['lastname']     = $userConnected['lastname'];
        $_SESSION['firstname']    = $userConnected['firstname'];
        $_SESSION['email']        = $userConnected['email'];
        $_SESSION['pseudo']       = $userConnected['pseudo'];
        $_SESSION['github']       = $userConnected['github'];
        $_SESSION['is_admin']     = $userConnected['is_admin'];
        $_SESSION['specialty']    = $userSpecialty['title'];
        $_SESSION['specialty_id'] = $userConnected['specialty_id'];
        $_SESSION['campus_id']    = $userConnected['campus_id'];
        $_SESSION['campus']       = $userCampus['city'];
    }
}
