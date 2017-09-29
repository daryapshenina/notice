<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170630084352 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $sql = <<<SQL
		CREATE TABLE public.login_user
		(
		  key text NOT NULL,
		  data text,
		  CONSTRAINT login_user_pkey PRIMARY KEY (key)
		)
SQL;
    	
    	$this->addSql($sql);

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$sql = <<<SQL
		DROP TABLE public.login_user
SQL;
    	
    	$this->addSql($sql);
    }
}
