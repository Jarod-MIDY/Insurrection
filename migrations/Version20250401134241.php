<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401134241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scene_story (id SERIAL NOT NULL, scene_id INT NOT NULL, player_id INT NOT NULL, body VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EC20664A166053B4 ON scene_story (scene_id)');
        $this->addSql('CREATE INDEX IDX_EC20664A99E6F5DF ON scene_story (player_id)');
        $this->addSql('ALTER TABLE scene_story ADD CONSTRAINT FK_EC20664A166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scene_story ADD CONSTRAINT FK_EC20664A99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scene DROP story');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE scene_story DROP CONSTRAINT FK_EC20664A166053B4');
        $this->addSql('ALTER TABLE scene_story DROP CONSTRAINT FK_EC20664A99E6F5DF');
        $this->addSql('DROP TABLE scene_story');
        $this->addSql('ALTER TABLE scene ADD story TEXT DEFAULT NULL');
    }
}
