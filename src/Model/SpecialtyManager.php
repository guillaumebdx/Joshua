<?php

namespace App\Model;

class SpecialtyManager extends AbstractManager
{
    const TABLE = 'specialty';

    /**
     * SpecialtyManager constructor.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param object $specialty
     * @return int
     */
    public function insertSpecialty(object $specialty): int
    {

        $query = 'INSERT INTO ' . self::TABLE . ' (title) VALUES (:title)';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':title', $specialty->getProperty('title'), \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * @return int
     */
    public function getTotalNumberOfSpecialties(): int
    {
        $query = 'SELECT count(*) as total FROM ' . self::TABLE;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $result = $statement->fetch();
        return $result['total'];
    }
}
