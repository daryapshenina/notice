<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170629104325 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
$this->addSql(
'CREATE SEQUENCE public.user_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;'

);

	$sql = <<<SQL
CREATE TABLE public."user"
(
  id integer NOT NULL DEFAULT nextval('user_id_seq'::regclass),
  name character varying(30),
  surname character varying(30),
  patronymic character varying(30),
  post character varying(50),
  rank character varying(30),
  CONSTRAINT user_pkey PRIMARY KEY (id)
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
	$this->addSql('DROP TABLE IF EXISTS public.user');
	$this->addSql('DROP SEQUENCE public.user_id_seq');
    }
}
