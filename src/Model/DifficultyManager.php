<?php

namespace App\Model;

class DifficultyManager extends AbstractManager
{
    const TABLE = 'difficulty';

    /**
     * DifficultyManager constructor.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
