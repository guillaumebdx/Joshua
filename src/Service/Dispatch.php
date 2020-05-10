<?php

namespace App\Service;

class Dispatch
{
    const ALLOWED_URL_FOR_NOT_REGISTERED_USER = ['/', '/user/register', '/user/insertuser'];
    const NOT_ALLOWED_URL_FOR_REGISTERED_USER = ['/joshua/index', '/user/register', '/'];

    public static function userNotRegistered(): void
    {
        $test1 = empty($_SESSION['pseudo']);
        $test2 = !in_array($_SERVER['REQUEST_URI'], self::ALLOWED_URL_FOR_NOT_REGISTERED_USER);

        if ($test1 && $test2) {
            header('Location: /');
            exit;
        }
    }

    public static function userRegistered(): void
    {
        $test1 = in_array($_SERVER['REQUEST_URI'], self::NOT_ALLOWED_URL_FOR_REGISTERED_USER);
        $test2 = isset($_SESSION['pseudo']);
        if ($test1 && $test2) {
            header('Location: /joshua/home');
            exit;
        }
    }

    public static function notAdminAndWantToGoAdminPanel(): void
    {
        $test1 = stristr($_SERVER['REQUEST_URI'], 'admin') != false;
        $test2 = (isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false) === false;

        if ($test1 && $test2) {
            header('Location: /');
            exit;
        }
    }

    public static function isSolutionPossible(bool $isPossible): void
    {
        if (!$isPossible) {
            header('Location:/contest/results/' . $contest);
            exit;
        }
    }

    public static function toUrl(string $url): void
    {
            header('Location:' . $url);
            exit;
    }
}
