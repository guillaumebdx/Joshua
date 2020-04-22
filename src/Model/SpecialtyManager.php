<?php


namespace App\Model;

class SpecialtyManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'specialty';

<<<<<<< HEAD

    public function insertSpecialty(object $specialty)
    {
        $query = 'INSERT INTO ' . $this->table. ' (title) VALUES (:title)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':title', $specialty->getProperty('title'), \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
=======
    const TABLE = 'specialty';
    public function __construct()
    {
        parent::__construct(self::TABLE);
>>>>>>> e4ccc398aec1246febf79781158fa60a15524096
    }
}
