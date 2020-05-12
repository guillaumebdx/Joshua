<?php

namespace App\Model;

class DifficultyManager extends AbstractManager
{
    const TABLE = 'difficulty';

    /**
     * <p>DifficultyManager constructor</p>.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
