<?php


namespace App\Model;

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

    public function addUser($data)
    {
        $query  = 'INSERT INTO ' . self::TABLE;
        $query .= ' (lastname, firstname, pseudo, github, email, password, specialty_id, campus_id)';
        $query .= ' VALUES (:lastname, :firstname, :pseudo, :github, :email, :password, :specialty, :campus)';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':lastname', $data['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':firstname', $data['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':pseudo', $data['joshuapseudo'], \PDO::PARAM_STR);
        $statement->bindValue(':github', $data['github'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $data['email'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $data['password'], \PDO::PARAM_STR);
        $statement->bindValue(':specialty', $data['specialty'], \PDO::PARAM_INT);
        $statement->bindValue(':campus', $data['campus'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        } else {
            return 'Impossible d\'ajouter l\'utilisateur : ';
        }
    }

    public function selectOneByEmail(string $email)
    {
        $statement = $this->pdo->prepare('SELECT * FROM ' . self::TABLE . ' WHERE email=:email');
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
