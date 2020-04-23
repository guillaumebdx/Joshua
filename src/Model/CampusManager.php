<?php


namespace App\Model;

class CampusManager extends AbstractManager
{
    const TABLE = 'campus';

    /**
     * @param object $campus
     * @return int
     */
    public function insertCampus(object $campus)
    {
        // prepared request
        $query = 'INSERT INTO ' . $this->table . ' (city, country, flag) VALUES (:city, :country, :flag)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':country', $campus->getProperty('country'), \PDO::PARAM_STR);
        $statement->bindValue(':city', $campus->getProperty('city'), \PDO::PARAM_STR);
        $flag = strtolower($campus->getProperty('country') . '.svg');
        $statement->bindValue(':flag', $flag, \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }
}
