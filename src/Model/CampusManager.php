<?php

namespace App\Model;

use FormControl\CampusFormControl;

class CampusManager extends AbstractManager
{
    const TABLE = 'campus';

    /**
     * <p>CampusManager constructor.</p>
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * <p>Select All campuses without tuple id = 0 -> 'all campus'</p>
     * @return array
     */
    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . self::TABLE . ' WHERE id != 0')->fetchAll();
    }

    /**
     * <p>Insert a new campus</p>
     * @param CampusFormControl $campus
     */
    public function insertCampus(CampusFormControl $campus)
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (city, country, flag) VALUES (:city, :country, :flag)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':country', $campus->getProperty('country'), \PDO::PARAM_STR);
        $statement->bindValue(':city', $campus->getProperty('city'), \PDO::PARAM_STR);
        $flag = strtolower($campus->getProperty('country') . '.svg');
        $statement->bindValue(':flag', $flag, \PDO::PARAM_STR);

        $statement->execute();
    }

    /**
     * <p>Get all campus by order.</p>
     * @param string $order1
     * @param string $sort1
     * @param string $order2 [optional]
     * @param string $sort2 [optional]
     * @return array
     */
    public function getAllCampusOrderBy(
        string $order1,
        string $sort1 = 'ASC',
        string $order2 = '',
        string $sort2 = 'ASC'
    ): array {
        $query = 'SELECT * FROM ' . self::TABLE . ' WHERE id != 0 ORDER BY ' . $order1 . ' ' . $sort1;
        if ($order2 != '') {
            $query .= ', ' . $order2 . ' ' . $sort2;
        }
        $statement = $this->pdo->query($query);
        return $statement->fetchAll();
    }

    /**
     * <p>Get the number of campus.</p>
     * @return int
     */
    public function getTotalNumberOfCampus(): int
    {
        $query = 'SELECT count(*) AS total FROM ' . self::TABLE;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $result = $statement->fetch();
        return $result['total'];
    }
}
