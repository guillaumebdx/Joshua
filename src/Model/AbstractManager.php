<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 20:52
 * PHP version 7
 */
namespace App\Model;

use App\Model\Connection;
use App\Service\TextProcessing;
use PDO;

/**
 * Abstract class handling default manager.
 */
abstract class AbstractManager
{
    /**
     * @var PDO
     */
    protected $pdo; //variable de connexion

    /**
     * @var string
     */
    protected $table;
    /**
     * @var string
     */
    protected $className;


    /**
     * Initializes Manager Abstract class.
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->table = $table;
        $this->className = __NAMESPACE__ . '\\' . ucfirst($table);
        $this->pdo = (new Connection())->getPdoConnection();
    }

    /**
     * Get all row from database.
     *
     * @return array
     */
    public function selectAll(): array
    {
        $query = 'SELECT * FROM ' . $this->table;
        return TextProcessing::decodeSpecialCharsInArray($this->pdo->query($query)->fetchAll(), true);
    }

    /**
     * Get one row from database by ID.
     *
     * @param  int $id
     *
     * @return array
     */
    public function selectOneById(int $id)
    {
        // prepared request
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return TextProcessing::decodeSpecialCharsInArray($statement->fetch());
    }
}
