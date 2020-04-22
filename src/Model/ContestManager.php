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
     * The User ID
     * @var int $user_id
     * The number of results you need. If empty, return all results
     * @var int $limit
     * The list of the contests played by user
     * @return Array
     */
    public function getContestsPlayedByUser(int $user, int $limit = 0) : array
    {
        $query =    'SELECT * FROM contest c ' .
                    ' JOIN user_has_contest uhc ON ' .
                    ' uhc.contest_id = c.id ' .
                    ' WHERE uhc.user_id = :user ' .
                    ' GROUP BY c.id ' .
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
     * @throws \Exception
     */
    public function getUserContestStartTime(int $user, int $contest)  : ?string
    {
        $query =    'SELECT started_on from user_has_contest ' .
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
     * @throws \Exception
     */
    public function getNumberFlagsPlayedByUserInContest(int $user, int $contest) : ?int
    {
        $query =    'SELECT count(challenge_id) as challenges_ended from user_has_contest ' .
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
     * @throws \Exception
     */
    public function getNumberOfChallengesInContest(int $contest) : ?int
    {
        $query = 'SELECT count(challenge_id) as total_challenges from contest_has_challenge where contest_id=:contest';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        if ($statement->execute()) {
            $result = $statement->fetch();
            return $result['total_challenges'];
        } else {
            throw new Exception('Impossible to get the number of challenges in this contest');
        }
    }
}
