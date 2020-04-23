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

    public function addUser(array $data)
    {
        $query  = 'INSERT INTO ' . self::TABLE;
        $query .= ' (lastname, firstname, pseudo, github, email, password, specialty_id, campus_id) ';
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
            throw new Exception('Unable to add user');
        }
    }

    public function updateUser(array $data)
    {
        $query  = 'UPDATE ' . self::TABLE;
        $query .= ' set lastname = :lastname, firstname = :firstname, pseudo = :pseudo, github = :github, ';
        $query .= ' email = :email, ';
        if ($data['password']!='') {
            $query.= ' password = :password, ';
        }
        $query.=' specialty_id = :specialty, campus_id = :campus ';
        $query.=' WHERE id = '.$_SESSION['user_id'];

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':lastname', $data['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':firstname', $data['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':pseudo', $data['joshuapseudo'], \PDO::PARAM_STR);
        $statement->bindValue(':github', $data['github'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $data['email'], \PDO::PARAM_STR);
        if ($data['password']!='') {
            $statement->bindValue(':password', $data['password'], \PDO::PARAM_STR);
        }
        $statement->bindValue(':specialty', $data['specialty'], \PDO::PARAM_INT);
        $statement->bindValue(':campus', $data['campus'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return;
        } else {
            throw new Exception('Unable to update user');
        }
    }

    public function selectOneByEmail(string $email)
    {
        $statement = $this->pdo->prepare('SELECT * FROM ' . self::TABLE . ' WHERE email=:email');
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * Field to sort on
     * @param string $orderBy
     * Sort order : ASC or DESC
     * @param string $sortOrder
     * @return array
     */

    public function selectAllOrderBy(string $orderBy, string $sortOrder): array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM ' . self::TABLE .
                      ' ORDER BY ' . $orderBy . ' ' . $sortOrder
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Status of user : Admin = 1 / no-admin = 0
     * @param int $status
     * The user ID
     * @param int $user
     */
    public function userSetAdmin(int $status, int $user) : void
    {
        $query = 'UPDATE user SET is_admin = :status WHERE id = :user';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':status', $status, \PDO::PARAM_INT);
        $statement->bindValue(':user', $user, \PDO::PARAM_INT);
        $statement->execute();
    }
}
