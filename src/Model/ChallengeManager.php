<?php

namespace App\Model;

use App\Service\ContestService;
use App\Service\Dispatch;
use FormControl\ChallengeFormControl;

class ChallengeManager extends AbstractManager
{
    const TABLE = 'challenge';

    /**
     * <p>ChallengeManager constructor.</p>
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * <p>Insert a new challenge.</p>
     * @param ChallengeFormControl $challenge
     */
    public function addChallenge(ChallengeFormControl $challenge): void
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (name, description, difficulty_id, type_id, url, flag)' .
            ' VALUES (:name, :description, :difficulty_id, :type_id, :url, :flag)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':name', $challenge->getProperty('name'), \PDO::PARAM_STR);
        $statement->bindValue(':description', $challenge->getProperty('description'), \PDO::PARAM_STR);
        $statement->bindValue(':difficulty_id', $challenge->getProperty('difficulty'), \PDO::PARAM_INT);
        $statement->bindValue(':type_id', $challenge->getProperty('type'), \PDO::PARAM_INT);
        $statement->bindValue(':url', $challenge->getProperty('url'), \PDO::PARAM_STR);
        $statement->bindValue(':flag', $challenge->getProperty('flag'), \PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * <p>Edit a existing challenge.</p>
     * @param ChallengeFormControl $challenge
     * @param int $id
     */
    public function editChallenge(ChallengeFormControl $challenge, int $id): void
    {
        $query = 'UPDATE ' . self::TABLE . ' SET name = :name, description = :description,' .
            ' difficulty_id = :difficulty_id, type_id = :type_id, url = :url, flag = :flag, updated_on = now()' .
            ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->bindValue(':name', $challenge->getProperty('name'), \PDO::PARAM_STR);
        $statement->bindValue(':description', $challenge->getProperty('description'), \PDO::PARAM_STR);
        $statement->bindValue(':difficulty_id', $challenge->getProperty('difficulty'), \PDO::PARAM_INT);
        $statement->bindValue(':type_id', $challenge->getProperty('type'), \PDO::PARAM_INT);
        $statement->bindValue(':url', $challenge->getProperty('url'), \PDO::PARAM_STR);
        $statement->bindValue(':flag', $challenge->getProperty('flag'), \PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * <p>Delete a existing challenge</p>
     * @param int $id
     */
    public function deleteChallenge(int $id): void
    {
        $query = 'DELETE FROM ' . self::TABLE . ' WHERE id = ' . $id;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
    }

    public function selectChallengesNotInContest(int $contestId): array
    {
        $query = 'SELECT * FROM ' . self::TABLE .
            ' WHERE id NOT IN' .
            ' (SELECT challenge_id FROM contest_has_challenge AS chc WHERE chc.contest_id = :contestId)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contestId', $contestId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * <p>Get all challenges in a contest.</p>
     * @param int $contest
     * @return array
     */
    public function getChallengesByContest(int $contest): array
    {
        $query = 'SELECT c.name, chc.challenge_id, chc.order_challenge FROM ' . ChallengeManager::TABLE . ' c' .
            ' JOIN ' . ContestHasChallengeManager::TABLE . ' chc ON chc.challenge_id = c.id' .
            ' WHERE chc.contest_id = :contest' .
            ' ORDER BY chc.order_challenge';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * <p>Recover the user's current challenge.</p>
     * @param int $contest
     * @return array|null
     * TODO -> VERIFY DATA
     */
    public function challengeOnTheWayByUser($contest)
    {
        $query = 'SELECT c.name, c.description, c.url, c.id, t.title AS type, d.title AS difficulty,' .
            ' chc.order_challenge FROM ' . self::TABLE . ' c ' .
            ' LEFT JOIN ' . UserHasContestManager::TABLE . ' uhc ON uhc.challenge_id = c.id' .
            ' JOIN ' . TypeManager::TABLE . ' t ON t.id = c.type_id' .
            ' JOIN ' . DifficultyManager::TABLE . ' d ON d.id = c.difficulty_id' .
            ' JOIN ' . ContestHasChallengeManager::TABLE . ' chc ON chc.challenge_id = c.id' .
            ' WHERE uhc.started_on IS NOT NULL AND uhc.ended_on IS NULL AND chc.contest_id = :contest' .
            ' AND uhc.user_id = ' . $_SESSION['user_id'] .
            ' AND uhc.contest_id = :contest';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        if (!empty($results[0])) {
            return $results[0];
        } else {
            if (!ContestService::isSolutionPossible($contest)) {
                Dispatch::toUrl('/contest/results/' . $contest);
            }
        }
    }

    /**
     * <p>Get the solution of a challenge</p>
     * @param int $challenge
     * @return string
     */
    public function getChallengeSolution(int $challenge): string
    {
        $query     = 'SELECT flag from ' . ChallengeManager::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $challenge, \PDO::PARAM_INT);
        $statement->execute();
        $challenge = $statement->fetch();
        return $challenge['flag'];
    }

    /**
     * <p>Get the number of challenge.</p>
     * @return int
     */
    public function getTotalCountChallenges(): int
    {
        $query = 'SELECT count(*) as total FROM ' . self::TABLE;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $result = $statement->fetch();
        return $result['total'];
    }
}
