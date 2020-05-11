<?php

namespace App\Model;

use Exception;

class ContestHasChallengeManager extends AbstractManager
{
    const TABLE = 'contest_has_challenge';

    /**
     * <p>ContestHasChallengeManager constructor.</p>
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * <p>Get the next challenge</p>
     * @param int $order
     * @param int $contest
     * @return bool|mixed
     */
    public function getNextChallengeToPlay(int $order, int $contest)
    {
        $query = 'SELECT challenge_id FROM ' . self::TABLE .
            ' WHERE contest_id = :contest AND order_challenge = :order LIMIT 1';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->bindValue(':order', $order, \PDO::PARAM_INT);
        $statement->execute();
        $nextChallenge = $statement->fetchAll();
        $return = null;
        $return = (!empty($nextChallenge)) ? $nextChallenge[0]['challenge_id'] : false;
        return $return;
    }


    /**
     * <p>The number of challenges in a contest.</p>
     * @param int $contest
     * The contest ID
     * @return int
     * @throws Exception
     */
    public function getNumberOfChallengesInContest(int $contest): ?int
    {
        $query = 'SELECT count(challenge_id) as total_challenges FROM ' . self::TABLE . ' WHERE contest_id = :contest';
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
     * @param int $id
     * @return array
     */
    public function selectChallengesByContestId(int $id): array
    {
        $query = 'SELECT c.name AS name, c.id AS id, c.difficulty_id AS difficulty ' .
            ' FROM ' . $this->table . ' AS chc' .
            ' JOIN challenge AS c ON c.id = chc.challenge_id ' .
            ' WHERE contest_id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
