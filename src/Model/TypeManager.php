<?php

namespace App\Model;

class TypeManager extends AbstractManager
{
    const TABLE = 'type';

    /**
     * TypeManager constructor.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
