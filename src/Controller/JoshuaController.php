<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\IndexFormControl;

class JoshuaController extends AbstractController
{
    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $login = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $connectUser = new IndexFormControl($_POST);
            $login = $connectUser->getProperty('email');
            $password = $connectUser->getProperty('password');
            if (count($connectUser->getErrors()) === 0) {
                $userManager = new UserManager();
                $user = $userManager->selectOneByEmail($login);
                if ($user) {
                    if ($login === $user['email']) {
                        if (password_verify($password, $user['password'])) {
                            $userController = new UserController();
                            $userController->openConnection($user['id']);
                            header('Location: Admin/index');
                        } else {
                            $error = 'Invalid password !';
                        }
                    }
                } else {
                    $error = 'This email doesn\'t exist !';
                }
            }
        }

        return $this->twig->render('Home/index.html.twig', [
            'login' => $login,
            'error' => $error,
        ]);
    }
}
