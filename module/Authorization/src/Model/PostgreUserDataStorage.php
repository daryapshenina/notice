<?php

namespace Authorization\Model;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Authorization\Entity\User;
use Authorization\Model\AbstractUserDataStorage;

use Authorization\Model\PGSql;

/**
 * Хранилище Postgre SQL
 *
 * @author SotnikovDS
 *
 */
class PostgreUserDataStorage extends AbstractUserDataStorage
{
    private $entityManager;
    private $dbSchema = 'public';

    public function __construct()
    {
        $cfg = require __DIR__ . '/../../config/module.config.php';

        $PGSql = new PGSql($cfg['doctrine']['connection']['orm_default']['params']);
        $this->entityManager = $PGSql->getEntityManager();
    }

    public function set(string $key, string $value): void
    {
        $insertSql = "INSERT INTO {$this->dbSchema}.login_user(key, data) VALUES(:s_key, :s_val)";
        $statement = $this->entityManager->getConnection()->prepare($insertSql);
        $statement->bindValue('s_key', $key);
        $statement->bindValue('s_val', $value);
        $statement->execute();
    }

    public function get(string $key)
    {
        $insertSql = "SELECT data FROM {$this->dbSchema}.login_user WHERE key = :s_key";
        $statement = $this->entityManager->getConnection()->prepare($insertSql);
        $statement->bindValue('s_key', $key);
        $statement->execute();

        $result = $statement->fetch();
        return $result['data'];
    }

}