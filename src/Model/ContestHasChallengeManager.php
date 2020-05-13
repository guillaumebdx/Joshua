<?php

namespace App\Model;

use App\Service\TextProcessing;
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

    public function selectChallengeInContestById(int $id)
    {
        $query = 'SELECT * FROM ' . self::TABLE . ' WHERE challenge_id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
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
        $query = 'SELECT c.name AS name, c.id AS id, c.difficulty_id AS difficulty, chc.order_challenge' .
            ' FROM ' . $this->table . ' AS chc' .
            ' JOIN challenge AS c ON c.id = chc.challenge_id' .
            ' WHERE contest_id = :id' .
            ' ORDER BY chc.order_challenge';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return TextProcessing::decodeSpecialCharsInArray($statement->fetchAll());
    }

    public function deleteChallengesInContest(int $id): void
    {
        $query = 'DELETE FROM ' . self::TABLE . ' WHERE contest_id = ' . $id;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
    }

    public function addChallengesInContest(array $challenges, int $contestId)
    {
        foreach ($challenges as $order => $challengeId) {
            $order++;
            $query = 'INSERT INTO ' . self::TABLE . ' (contest_id, challenge_id, order_challenge) VALUES ';
            $query .= '(:contest, ' . $challengeId . ', ' . $order . ')';
            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':contest', $contestId, \PDO::PARAM_INT);
            $statement->execute();
        }
    }
}
