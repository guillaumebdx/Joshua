<?php

namespace App\Model;

class ContestManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'contest';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
