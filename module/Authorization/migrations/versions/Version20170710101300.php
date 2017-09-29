<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170710101300 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $sql = <<<SQL
        CREATE TABLE user_data(
            id serial PRIMARY KEY,
            hash VARCHAR (64) UNIQUE NOT NULL,
            data text NOT NULL,
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
        DROP TABLE user_data
SQL;

        $this->addSql($sql);
    }
}
