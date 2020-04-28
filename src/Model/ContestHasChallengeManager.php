<?php


namespace App\Model;

class ContestHasChallengeManager extends AbstractManager
{
    const TABLE = 'contest_has_challenge';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
