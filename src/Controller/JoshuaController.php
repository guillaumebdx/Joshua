<?php

//TODO Mettre en place la connexion utilisateur

namespace App\Controller;

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
        $errors = [];
        $login  = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new IndexFormControl($_POST);
            $errors = $user->getErrors();
            $login = $user->getProperty('email');
        }

        return $this->twig->render('Home/index.html.twig', [
            'errors' => $errors,
            'login'  => $login,
        ]);
    }
}
