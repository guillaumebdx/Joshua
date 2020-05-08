<?php

namespace App\Model;

use \Exception;

class UserManager extends AbstractManager
{
    /**
     * @const table name
     */
    const TABLE = 'user';
    const LIMIT_LIST_USERS = 4;

    /**
     * UserManager constructor.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectOneById(int $id): array
    {
        $query = 'SELECT u.id, is_admin, is_active, u.lastname, u.firstname, u.pseudo, u.github, u.email, u.password,'
            . ' u.specialty_id, s.title AS specialty, u.campus_id, c.city AS campus, u.created_on, u.updated_on'
            . ' FROM ' . self::TABLE . ' u'
            . ' JOIN ' . SpecialtyManager::TABLE . ' s ON s.id = u.specialty_id'
            . ' JOIN ' . CampusManager::TABLE . ' c ON c.id = u.campus_id'
            . ' WHERE u.id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * @param array $data
     * @return int
     * @throws Exception
     */
    public function addUser(array $data): int
    {
        $query  = 'INSERT INTO ' . self::TABLE .
            ' (lastname, firstname, pseudo, github, email, password, specialty_id, campus_id)' .
            ' VALUES (:lastname, :firstname, :pseudo, :github, :email, :password, :specialty, :campus)';

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

    /**
     * @param array $data
     */
    public function updateUser(array $data): void
    {
        $query  = 'UPDATE ' . self::TABLE .
            ' set lastname = :lastname, firstname = :firstname, pseudo = :pseudo, github = :github, email = :email,';
        if ($data['password']!='') {
            $query .= ' password = :password, ';
        }
        $query .= ' specialty_id = :specialty, campus_id = :campus' .
            ' WHERE id = ' . $_SESSION['user_id'];

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':lastname', $data['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':firstname', $data['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':pseudo', $data['joshuapseudo'], \PDO::PARAM_STR);
        $statement->bindValue(':github', $data['github'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $data['email'], \PDO::PARAM_STR);
        if ($data['password'] != '') {
            $statement->bindValue(':password', $data['password'], \PDO::PARAM_STR);
        }
        $statement->bindValue(':specialty', $data['specialty'], \PDO::PARAM_INT);
        $statement->bindValue(':campus', $data['campus'], \PDO::PARAM_INT);

        $statement->execute();
    }

    /**
     * @param string $pseudo
     * @return mixed
     */
    public function selectOneByPseudo(string $pseudo)
    {
        $query     = 'SELECT * FROM ' . self::TABLE . ' WHERE pseudo=:pseudo';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':pseudo', $pseudo, \PDO::PARAM_STR);
        if ($statement->execute()) {
            return $statement->fetch();
        }
    }

    /**
     * Total number of users
     * @param int $excluded
     * @return int
     */
    public function getTotalUsers($excluded = 0): int
    {
        $query     = 'SELECT count(id) AS total FROM ' . self::TABLE;
        if ($excluded != 0) {
            $query .= ' WHERE id != ' . $excluded;
        }
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $results   = $statement->fetchAll();
        return $results[0]['total'];
    }

    /**
     * @return int
     */
    public function numberOfPages(): int
    {
        $nbPagesPre = $this->getTotalUsers($_SESSION['user_id']) / self::LIMIT_LIST_USERS;
        $nbPages = ceil($nbPagesPre);
        return $nbPages;
    }

    /**
     * Field to sort on
     * @param string $orderBy
     * Sort order : ASC or DESC
     * @param string $sortOrder
     * @param int $page
     * @return array
     */
    public function selectAllOrderBy(string $orderBy, string $sortOrder, int $page = 1, $excluded = 0): array
    {
        $offset = ($page-1) * self::LIMIT_LIST_USERS;
        $query  = 'SELECT * FROM ' . self::TABLE ;
        if ($excluded != 0) {
            $query .= ' WHERE id != ' . $excluded;
        }
        $query .=   ' ORDER BY ' . $orderBy . ' ' . $sortOrder .
            ' LIMIT ' . self::LIMIT_LIST_USERS . ' OFFSET ' . $offset;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Status of user : Admin = 1 / no-admin = 0
     * @param int $status
     * The user ID
     * @param int $user
     */
    public function userSetAdmin(int $status, int $user): void
    {
        $query = 'UPDATE ' . self::TABLE . ' SET is_admin = :status WHERE id = :user';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':status', $status, \PDO::PARAM_INT);
        $statement->bindValue(':user', $user, \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param int $status
     * @param int $user
     */
    public function userSetActive(int $status, int $user): void
    {
        $query = 'UPDATE ' . self::TABLE . ' SET is_active = :status WHERE id = :user';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':status', $status, \PDO::PARAM_INT);
        $statement->bindValue(':user', $user, \PDO::PARAM_INT);
        $statement->execute();
    }
}
