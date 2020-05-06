<?php

namespace App\Model;

use mysql_xdevapi\TableSelect;

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
     * @param object $type
     * @return void
     */
    public function insertType(object $type): void
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (title) VALUES (:title)';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':title', $type->getProperty('title'), \PDO::PARAM_STR);
        $statement->execute();
    }
    public function typeExists(object $type): bool
    {
        $type  = trim($type->getProperty('title'));
        $query = 'SELECT * FROM ' . self::TABLE . ' WHERE title = :title';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':title', $type, \PDO::PARAM_STR);
        $statement->execute();
        if ($statement->fetch()) {
            return true;
        } else {
            return false;
        }
    }
}
