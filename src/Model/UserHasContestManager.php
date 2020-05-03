<?php

namespace App\Model;

class UserHasContestManager extends AbstractManager
{
    const TABLE = 'user_has_contest';

    /**
     * UserHasContestManager constructor.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
