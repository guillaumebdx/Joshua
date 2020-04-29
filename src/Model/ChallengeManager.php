<?php


namespace App\Model;

use Exception;

class ChallengeManager extends AbstractManager
{
    const TABLE = 'challenge';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function getChallengesByContest(int $contest) :array
    {
        $query = 'SELECT c.name, chc.challenge_id, chc.order_challenge FROM ' . ChallengeManager::TABLE . ' c ' .
                 'jOIN ' . ContestHasChallengeManager::TABLE . ' chc ON chc.challenge_id = c.id ' .
                 'WHERE chc.contest_id = :contest ' .
                 'ORDER BY chc.order_challenge';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->execute();
        $results  = $statement->fetchAll();
        return $results;
    }

    public function challengeStatus(int $challenge, int $contest) :string
    {
        $query = 'SELECT started_on, ended_on FROM ' . UserHasContestManager::TABLE .
                 ' where contest_id = :contest and challenge_id = :challenge and user_id = ' . $_SESSION['user_id'];
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->bindValue(':challenge', $challenge, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch();

        if (is_null($result['ended_on']) && !is_null($result['started_on'])) {
            return 'doing';
        } elseif (!is_null($result['ended_on']) && !is_null($result['started_on'])) {
            return 'done';
        } else {
            return 'todo';
        }
    }

    public function challengeTimeToSucceedByUser(int $challenge, int $contest) :?string
    {
        $query = 'SELECT TIMEDIFF(ended_on, started_on) AS time FROM ' . UserHasContestManager::TABLE .
                 ' where contest_id = :contest and challenge_id = :challenge and user_id = ' . $_SESSION['user_id'];
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->bindValue(':challenge', $challenge, \PDO::PARAM_INT);
        $statement->execute();
        $time = $statement->fetch();
        return $time['time'];
    }

    public function challengeOnTheWayByUser(int $contest)
    {
        $query = 'SELECT ' .
                 ' c.name, c.description, c.url, c.id,' .
                 ' t.title AS type,' .
                 ' d.title as difficulty,' .
                 ' chc.order_challenge ' .
                 ' FROM ' . ChallengeManager::TABLE . ' c ' .
                 ' LEFT JOIN ' . UserHasContestManager::TABLE . ' uhc ON uhc.challenge_id = c.id ' .
                 ' JOIN ' . TypeManager::TABLE . ' t ON t.id = c.type_id' .
                 ' JOIN ' . DifficultyManager::TABLE . ' d ON d.id = c.difficulty_id' .
                 ' JOIN ' . ContestHasChallengeManager::TABLE . ' chc ON chc.challenge_id = c.id ' .
                 ' WHERE uhc.started_on is not null AND uhc.ended_on is null AND chc.contest_id = :contest' .
                 ' and uhc.user_id = ' . $_SESSION['user_id'] .
                 ' and uhc.contest_id = :contest';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->execute();
        $challenge = $statement->fetch();
        return $challenge;
    }


    public function getChallengeSolution(int $challenge) : string
    {
        $query = 'SELECT flag from '.ChallengeManager::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $challenge, \PDO::PARAM_INT);
        $statement->execute();
        $challenge = $statement->fetch();
        return $challenge['flag'];
    }

    public function registerChallengeSuccess(int $challenge, int $contest) : void
    {
        $query = 'UPDATE ' . UserHasContestManager::TABLE .
            ' SET ended_on = :now ' .
            ' WHERE user_id = :user and contest_id = :contest and challenge_id = :challenge ';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':now', date('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $statement->bindValue(':user', $_SESSION['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->bindValue(':challenge', $challenge, \PDO::PARAM_INT);
        if (!$statement->execute()) {
            throw new Exception('Impossible d\'enregistrer votre victoire');
        }
    }

    public function getNextChallengeToPlay(int $order, int $contest)
    {
        $query = 'SELECT challenge_id FROM ' . ContestHasChallengeManager::TABLE .
                 ' WHERE contest_id = :contest and order_challenge = :order LIMIT 1';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->bindValue(':order', $order, \PDO::PARAM_INT);
        $statement->execute();
        $nextChallenge = $statement->fetch();
        if (!is_null($nextChallenge)) {
            return $nextChallenge['challenge_id'];
        } else {
            return false;
        }
    }

    public function startNextChallenge(int $challenge, int $contest) : void
    {
        $query = 'INSERT INTO ' . UserHasContestManager::TABLE .
                 ' (user_id, contest_id, challenge_id, started_on) ' .
                 ' VALUES (:user, :contest, :challenge, :startdate)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contest, \PDO::PARAM_INT);
        $statement->bindValue(':challenge', $challenge, \PDO::PARAM_INT);
        $statement->bindValue(':user', $_SESSION['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':startdate', date('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $statement->execute();
    }
}
