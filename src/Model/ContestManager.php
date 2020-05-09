<?php

namespace App\Model;

use App\Service\ContestDate;
use Exception;
use FormControl\ContestFormControl;

class ContestManager extends AbstractManager
{
    const TABLE        = 'contest';
    const NOT_ENDED    = 1;
    const STARTED      = 2;
    const ENDED        = 3;
    const ONLY_VISIBLE = true;

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * <p>Get all contests according to the requested parameter.</p>
     * @param int $status [optional]<br>
     * Put <b>ContestManager::NOT_ENDED</b> for all contest minus those finished.<br>
     * Put <b>ContestManager::STARTED</b> for all contest started.<br>
     * Put <b>ContestManager::ENDED</b> for all finished contest.</>
     * @param bool $isVisible [optional]<br>
     * <p>Put <b>ContestManager::ONLY_VISIBLE</b>, used only for <b>ContestManager::NOT_ENDED</b><br>
     * Allows you to refine the selection to only those visible.</p>
     * @return array
     */
    public function selectAll(int $status = null, bool $isVisible = null): array
    {
        $query = 'SELECT c.id, c.is_visible, c.is_active, c.name, c.image, c.description, c.duration, c.campus_id,' .
            ' ca.city AS campus, ca.flag, c.created_on, c.started_on' .
            ' FROM ' . self::TABLE . ' c' .
            ' JOIN ' . CampusManager::TABLE . ' ca ON ca.id = campus_id';

        if ($status === self::NOT_ENDED) {
            $query .= ' WHERE (started_on IS NULL OR NOW() < DATE_ADD(c.started_on,interval c.duration minute))';
            if ($isVisible === true) {
                $query .= ' AND c.is_visible = 1';
            }
        } elseif ($status === self::STARTED) {
            $query .= ' WHERE started_on IS NOT NULL AND NOW() < DATE_ADD(c.started_on,interval c.duration minute)';
        } elseif ($status === self::ENDED) {
            $query .= ' WHERE NOW() > DATE_ADD(c.started_on,interval c.duration minute)';
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * <p>Get the number of contest.</p>
     * @return int
     */
    public function getTotalNumberOfContest(): int
    {
        $query = 'SELECT count(*) as total FROM ' . self::TABLE;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $result = $statement->fetch();
        return $result['total'];
    }

    /**
     * <p>Insert a new contest.</p>
     * @param ContestFormControl $contest
     * @return int
     */
    public function addContest(ContestFormControl $contest): int
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (name, campus_id, description, duration, image)' .
            ' VALUES (:name, :campus, :description, :duration, :image)';
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
     * <p>Edit a existing contest.</p>
     * @param object $contest
     * @param int $id
     * @return int
     */
    public function editContest(object $contest, int $id): int
    {
        $query = 'UPDATE ' . self::TABLE . ' SET name = :name, campus_id = :campus, description = :description,' .
            ' duration = :duration, image = :image' .
            ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
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
     * <p>Make a not started contest visible.</p>
     * @param int $id
     */
    public function displayOnForContest(int $id): void
    {
        $query = 'UPDATE ' . self::TABLE . ' SET is_visible = 1 WHERE id = ' . $id;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
    }

    /**
     * <p>Make a not started contest invisible.</p>
     * @param int $id
     */
    public function displayOffForContest(int $id): void
    {
        $query = 'UPDATE ' . self::TABLE . ' SET is_visible = 0 WHERE id = ' . $id;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
    }

    /**
     * <p>Delete a existing contest</p>
     * @param int $id
     */
    public function deleteContest(int $id): void
    {
        $query = 'DELETE FROM ' . self::TABLE . ' WHERE id = ' . $id;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
    }

    /**
     * <p>The list of the contests played by user</p>
     * @var int $user
     * <p>The User ID</p>
     * @var int $limit [optional]<br>
     * <p>The number of results you need. If empty, return all results</p>
     * @return array
     */
    public function getContestsPlayedByUser(int $user, int $limit = 0): array
    {
        $query = 'SELECT distinct c.id, c.name  FROM ' . self::TABLE . ' c ' .
            ' JOIN ' . UserHasContestManager::TABLE . ' uhc ON ' .
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
     * <p>Get the date and time (format : aaaa-mm-jj H:i:s) of when the user as started his first challenge in this contest.</p>
     * @param int $user
     * <p>The user ID.</p>
     * @param int $contest
     * <p>The contest ID.</p>
     * @return string
     * @throws Exception
     */
    public function getUserContestStartTime(int $user, int $contest): ?string
    {
        $query = 'SELECT started_on FROM ' . UserHasContestManager::TABLE .
            ' WHERE user_id = :user AND contest_id = :contest ORDER BY started_on LIMIT 1';
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
     * <p>Active a contest.</p>
     * @param string $contestId
     */
    public function setContestActive(string $contestId): void
    {
        $query = 'UPDATE ' . self::TABLE .
            ' SET is_active = 1, started_on = now()' .
            ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $contestId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function getActiveContests(): array
    {
        $query = 'SELECT * FROM ' . self::TABLE .
            ' WHERE is_active = 1 ' .
            ' AND TIMEDIFF(NOW(), started_on) < SEC_TO_TIME(duration * 60)';
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $activeContests = $statement->fetchAll();
        foreach ($activeContests as $key => $contest) {
            $activeContests[$key]['end_date'] = ContestDate::getContestEndDate(
                $contest['started_on'],
                $contest['duration']
            );
        }
        return $activeContests;
    }
}
