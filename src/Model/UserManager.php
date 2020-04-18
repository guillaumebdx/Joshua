<?php


namespace App\Model;

use \Exception;

class UserManager extends AbstractManager
{
    /**
     * @const table name
     */
    const TABLE = 'user';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function addUser($datas)
    {
        $query  = 'INSERT INTO ' . self::TABLE;
        $query .= ' (lastname, firstname, pseudo, github, email, email_confirm, password, specialty_id, campus_id) ';
        $query .= ' VALUES (:lastname, :firstname, :pseudo, :github, :email, ';
        $query .= ':email_confirm, :password, :specialty, :campus)';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':lastname', $datas['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':firstname', $datas['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':pseudo', $datas['joshuapseudo'], \PDO::PARAM_STR);
        $statement->bindValue(':github', $datas['github'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $datas['email'], \PDO::PARAM_STR);
        $statement->bindValue(':email_confirm', 1, \PDO::PARAM_INT);
        $statement->bindValue(':password', $datas['password'], \PDO::PARAM_STR);
        $statement->bindValue(':specialty', $datas['specialty'], \PDO::PARAM_INT);
        $statement->bindValue(':campus', $datas['campus'], \PDO::PARAM_INT);

        try {
            $statement->execute();
            return (int)$this->pdo->lastInsertId();
        } catch (Exception $e) {
            echo 'Impossible d\'ajouter l\'utilisateur : ' . $e->getMessage() ;
        }
    }
}
