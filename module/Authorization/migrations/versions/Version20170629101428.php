<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170629101428 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $sql = <<<SQL
        CREATE TABLE token(
         id serial PRIMARY KEY,
         token VARCHAR (64) UNIQUE NOT NULL,
         uptime timestamp with time zone
        );
SQL;

        $this->addSql($sql);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $sql = <<<SQL
        DROP TABLE token
SQL;

        $this->addSql($sql);
    }
}
