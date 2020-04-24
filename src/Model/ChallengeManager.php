<?php


namespace App\Model;

class ChallengeManager extends AbstractManager
{
    const TABLE = 'challenge';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
