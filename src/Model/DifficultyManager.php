<?php


namespace App\Model;

class DifficultyManager extends AbstractManager
{
    const TABLE = 'difficulty';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
