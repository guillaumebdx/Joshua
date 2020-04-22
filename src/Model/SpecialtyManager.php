<?php


namespace App\Model;

class SpecialtyManager extends AbstractManager
{

    const TABLE = 'specialty';
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
