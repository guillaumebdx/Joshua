<?php


namespace App\Model;

class TypeManager extends AbstractManager
{
    const TABLE = 'type';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
