<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414122951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE token_action (id SERIAL NOT NULL, player_id INT NOT NULL, scene_id INT NOT NULL, action_obtain VARCHAR(255) DEFAULT NULL, action_suffer VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_46B4239099E6F5DF ON token_action (player_id)');
        $this->addSql('CREATE INDEX IDX_46B42390166053B4 ON token_action (scene_id)');
        $this->addSql('ALTER TABLE token_action ADD CONSTRAINT FK_46B4239099E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE token_action ADD CONSTRAINT FK_46B42390166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE influence_token DROP linked_role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE token_action DROP CONSTRAINT FK_46B4239099E6F5DF');
        $this->addSql('ALTER TABLE token_action DROP CONSTRAINT FK_46B42390166053B4');
        $this->addSql('DROP TABLE token_action');
        $this->addSql('ALTER TABLE influence_token ADD linked_role VARCHAR(255) NOT NULL');
    }
}
