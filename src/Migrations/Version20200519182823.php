<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519182823 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE image_post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE image_post (id INT NOT NULL, post_id INT DEFAULT NULL, usuario_id INT DEFAULT NULL, image_name VARCHAR(511) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A8B086134B89032C ON image_post (post_id)');
        $this->addSql('CREATE INDEX IDX_A8B08613DB38439E ON image_post (usuario_id)');
        $this->addSql('ALTER TABLE image_post ADD CONSTRAINT FK_A8B086134B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image_post ADD CONSTRAINT FK_A8B08613DB38439E FOREIGN KEY (usuario_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE image_post_id_seq CASCADE');
        $this->addSql('DROP TABLE image_post');
    }
}
