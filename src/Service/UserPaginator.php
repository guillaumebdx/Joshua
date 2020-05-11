<?php

namespace App\Service;

use App\Model\UserManager;

class UserPaginator
{
    const LIMIT_LIST_USERS = 10;

    /**
     * @param UserManager $instance
     * @return int
     */
    public static function numberOfPages(UserManager $instance): int
    {
        $nbPagesPre = $instance->getTotalUsers($_SESSION['user_id']) / self::LIMIT_LIST_USERS;
        return ceil($nbPagesPre);
    }
}
