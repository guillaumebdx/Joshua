<?php

namespace App\Model;

class CampusManager extends AbstractManager
{
    const TABLE = 'campus';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @return array
     */
    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . self::TABLE . ' WHERE id != 0')->fetchAll();
    }

    public function insertCampus(object $campus)
    {
        // prepared request
        $query = 'INSERT INTO ' . self::TABLE . ' (city, country, flag) VALUES (:city, :country, :flag)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':country', $campus->getProperty('country'), \PDO::PARAM_STR);
        $statement->bindValue(':city', $campus->getProperty('city'), \PDO::PARAM_STR);
        $flag = strtolower($campus->getProperty('country') . '.svg');
        $statement->bindValue(':flag', $flag, \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function getAllCampusOrderBy(string $order1, string $sort1 = 'ASC', string $order2 = '', string $sort2 = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . self::TABLE . ' WHERE id != 0 ORDER BY ' . $order1 . ' ' . $sort1;
        if ($order2 != '') {
            $query .= ', ' . $order2 . ' ' . $sort2;
        }
        $statement = $this->pdo->query($query);
        return $statement->fetchAll();
    }
    public function campusExists(object $campus): bool
    {
        $campus = trim($campus->getProperty('city'));
        $query  = 'SELECT campus.city FROM ' . self::TABLE . ' WHERE city = :city';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':city', $campus, \PDO::PARAM_STR);
        $statement->execute();
        if ($statement->fetch()) {
            return true;
        } else {
            return false;
        }
    }
}
