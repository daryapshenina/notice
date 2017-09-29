<?php
/**
 * Created by PhpStorm.
 * User: altarix
 * Date: 23.06.17
 * Time: 16:45
 */

namespace Authorization\Model;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

use Authorization\Interfaces\TokenStorageInterface;

use Authorization\Entity\tokenModel;

class PGTokenStorage implements TokenStorageInterface
{

    /* @var $driver EntityManager */
    private $driver;

    private $config;

    public function __construct($config)
    {
        $this->config = $config;

        $paths = array(__DIR__ . '/../../src/');
        $isDevMode = false;

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $entityManager = EntityManager::create($this->config, $config);
        $this->driver = $entityManager;
    }

    /**
     * Вставим токен
     * @return array | boolean inser_id и token
     * */
    public function setToken($token)
    {
        $tokenObject = new tokenModel();
        $tokenObject->setToken($token);
        $tokenObject->setUptime(date('c'));

        $this->driver->persist($tokenObject);
        $this->driver->flush();

        return $tokenObject->getId() ? array('insert_id' => $tokenObject->getId(), 'token' => $tokenObject->getToken()) : false;
    }

    /**
     * Проверим токен
     * @return array | boolean
     * */
    public function checkToken($token)
    {
        return $this->driver->getRepository(tokenModel::class)->findBy(array('token' => $token));
    }

    /**
     * Удалим токен
     * @return mixed
     * */
    public function deleteToken($token)
    {
        $qb = $this->driver->createQueryBuilder();
        $result = $qb->delete(tokenModel::class, 'tokenModel')
            ->where('tokenModel.token = :token')
            ->setParameter('token', $token);

        return $result->getQuery()->getResult();
    }
}