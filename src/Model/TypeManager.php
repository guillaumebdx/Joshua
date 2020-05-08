<?php

namespace App\Model;

use mysql_xdevapi\TableSelect;
use App\Service\TypeFormControl;

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

    /**
     * @param TypeFormControl $type
     * @return void
     */
    public function insertType(TypeFormControl $type): void
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (title) VALUES (:title)';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':title', $type->getProperty('title'), \PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * @param TypeFormControl $type
     * @return bool
     */
    public function typeExists(TypeFormControl $type): bool
    {
        $type  = trim($type->getProperty('title'));
        $query = 'SELECT * FROM ' . self::TABLE . ' WHERE title = :title';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':title', $type, \PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetchAll();
        $return = (!empty($results)) ? true : false;
        return $return;
    }
}
