<?php

namespace App\Model;

use \Exception;

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

    /**
     * @param object $contest
     * @return int
     */
    public function addContest(object $contest)
    {
        $query     = 'INSERT INTO ' . self::TABLE;
        $query    .= ' (name, campus_id, description, duration, image)';
        $query    .= ' VALUES (:name, :campus, :description, :duration, :image)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':name', $contest->getProperty('name'), \PDO::PARAM_STR);
        $statement->bindValue(':campus', $contest->getProperty('campus'), \PDO::PARAM_INT);
        $statement->bindValue(':description', $contest->getProperty('description'), \PDO::PARAM_STR);
        $statement->bindValue(':duration', $contest->getProperty('duration'), \PDO::PARAM_INT);
        $statement->bindValue(':image', $contest->getProperty('image'), \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * The User ID
     * @var int $limit
     * The list of the contests played by user
     * @var int $user
     * The number of results you need. If empty, return all results
     * @return array
     */
    public function getContestsPlayedByUser(int $user, int $limit = 0): array
    {
        $query = 'SELECT distinct c.id, c.name  FROM contest c ' .
            ' JOIN user_has_contest uhc ON ' .
            ' uhc.contest_id = c.id ' .
            ' WHERE uhc.user_id = :user ' .
            ' ORDER BY c.name ';
        if ($limit != 0) {
            $query .= ' LIMIT ' . $limit;
        }
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':user', $user, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * The user ID
     * @param int $user
     * The contest ID
     * @param int $contest
     * The date and time aaaa-mm-jj H:i:s when the user started his first challenge in this contest
     * @return string
     * @throws Exception
     */
    public function getUserContestStartTime(int $user, int $contest): ?string
    {
        $query = 'SELECT started_on from user_has_contest ' .
            'where user_id=:user and contest_id=:contest order by started_on LIMIT 1';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':user', $user, \PDO::PARAM_INT);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);

        if ($statement->execute()) {
            $result = $statement->fetch();
            return $result['started_on'];
        } else {
            throw new Exception('Impossible to get the started date');
        }
    }

    /**
     * the user ID
     * @param int $user
     * the contest ID
     * @param int $contest
     * The number of challenges played by user in this contest
     * @return int
     * @throws Exception
     */
    public function getNumberFlagsPlayedByUserInContest(int $user, int $contest): ?int
    {
        $query = 'SELECT count(challenge_id) as challenges_ended from user_has_contest ' .
            'where user_id=:user and contest_id=:contest and ended_on IS NOT NULL ';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':user', $user, \PDO::PARAM_INT);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);

        if ($statement->execute()) {
            $result = $statement->fetch();
            return $result['challenges_ended'];
        } else {
            throw new Exception('Impossible to get the number of played challenges');
        }
    }

    /**
     * The contest ID
     * @param int $contest
     * The number of challenges in this contest
     * @return int
     * @throws Exception
     */
    public function getNumberOfChallengesInContest(int $contest): ?int
    {
        $query = 'SELECT count(challenge_id) as total_challenges ' .
            ' from contest_has_challenge where contest_id=:contest';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        if ($statement->execute()) {
            $result = $statement->fetch();
            return $result['total_challenges'];
        } else {
            throw new Exception('Impossible to get the number of challenges in this contest');
        }
    }

    /**
     * the contest ID
     * @param int $contest
     * The palmares of the contest.
     * Returns user_id
     * and total_time (the total time used to get the flags)
     * and flags_number, total of flags resolved
     * @return array|null
     * @throws Exception
     */
    public function getContestPalmares(int $contest): ?array
    {
        $query = 'SELECT user_id, SUM(TIMEDIFF(ended_on, started_on)) AS total_time, ' .
            ' COUNT(challenge_id) AS flags_succeed ' .
            ' FROM user_has_contest WHERE contest_id = :contest and ended_on IS NOT NULL ' .
            ' GROUP BY user_id ORDER BY flags_succeed DESC, total_time ASC ';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->execute();
        $results  = $statement->fetchAll();
        $palmares = [];
        foreach ($results as $user) {
            $palmares[$user['user_id']] = [
                'total_time'    => $user['total_time'],
                'flags_succeed' => $user['flags_succeed'],
            ];
        }
        return $palmares;
    }
}
