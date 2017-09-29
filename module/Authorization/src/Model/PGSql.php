<?php

namespace Authorization\Model;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class PGSql
{

    /* @var $em EntityManager */
    private $em;

    /* @var $model */
    private $model;

    public function __construct($cfg)
    {
        $paths = array(__DIR__ . '/../../src/');
        $isDevMode = true;

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $entityManager = EntityManager::create($cfg, $config);
        $this->em = $entityManager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @var QueryBuilder
     * */
    public function QueryBuilder()
    {
        return $this->em->createQueryBuilder();
    }
}