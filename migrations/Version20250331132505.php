<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331132505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scene_leader_vote (id SERIAL NOT NULL, player_id INT NOT NULL, scene_id INT NOT NULL, voted_for_player_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A94EED4999E6F5DF ON scene_leader_vote (player_id)');
        $this->addSql('CREATE INDEX IDX_A94EED49166053B4 ON scene_leader_vote (scene_id)');
        $this->addSql('CREATE INDEX IDX_A94EED49A6B9C8C8 ON scene_leader_vote (voted_for_player_id)');
        $this->addSql('ALTER TABLE scene_leader_vote ADD CONSTRAINT FK_A94EED4999E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scene_leader_vote ADD CONSTRAINT FK_A94EED49166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scene_leader_vote ADD CONSTRAINT FK_A94EED49A6B9C8C8 FOREIGN KEY (voted_for_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character ALTER is_dead DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE scene_leader_vote DROP CONSTRAINT FK_A94EED4999E6F5DF');
        $this->addSql('ALTER TABLE scene_leader_vote DROP CONSTRAINT FK_A94EED49166053B4');
        $this->addSql('ALTER TABLE scene_leader_vote DROP CONSTRAINT FK_A94EED49A6B9C8C8');
        $this->addSql('DROP TABLE scene_leader_vote');
        $this->addSql('ALTER TABLE character ALTER is_dead SET DEFAULT false');
    }
}
