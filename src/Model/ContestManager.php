<?php

namespace App\Model;

class ContestManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'contest';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function addContest(object $contest)
    {
        $query  = 'INSERT INTO ' . self::TABLE;
        $query .= ' (name, campus, description, duration, image)';
        $query .= ' VALUES (:name, :campus, :description, :duration, :image)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':name', $contest->getProperty('name'), \PDO::PARAM_STR);
        $statement->bindValue(':campus', $contest->getProperty('campus'), \PDO::PARAM_INT);
        $statement->bindValue(':description', $contest->getProperty('description'), \PDO::PARAM_STR);
        $statement->bindValue(':duration', $contest->getProperty('duration'), \PDO::PARAM_INT);
        $statement->bindValue(':image', $contest->getProperty('url'), \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }
}
