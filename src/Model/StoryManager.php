<?php


namespace App\Model;

class StoryManager extends AbstractManager
{
    const TABLE = 'story';

    /**
     * <p>StoryManager constructor.</p>
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * <p>Get the history of a contest.</p>
     * @param int $contestId
     * @return array
     */
    public function getHistory(int $contestId): array
    {
        $query = 'SELECT u.pseudo, ch.name AS challenge, s.success, s.added_on' .
            ' FROM ' . self::TABLE . ' s' .
            ' JOIN ' . UserManager::TABLE . ' u ON u.id = s.user_id' .
            ' JOIN ' . ChallengeManager::TABLE . ' ch ON ch.id = s.challenge_id' .
            ' WHERE s.contest_id = :contest'  .
            ' ORDER BY added_on DESC';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':contest', $contestId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * <p>Insert a new entry in the history of a contest.</p>
     * @param int $userId
     * @param int $contestId
     * @param int $challengeId
     * @param int $success
     */
    public function setHistory(int $userId, int $contestId, int $challengeId, int $success): void
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (user_id, contest_id, challenge_id, success, added_on)' .
            ' VALUES (:user, :contest, :challenge, :success, :now)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':user', $userId, \PDO::PARAM_INT);
        $statement->bindValue(':contest', $contestId, \PDO::PARAM_INT);
        $statement->bindValue(':challenge', $challengeId, \PDO::PARAM_INT);
        $statement->bindValue(':success', $success, \PDO::PARAM_INT);
        $statement->bindValue(':now', date('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $statement->execute();
    }
}
