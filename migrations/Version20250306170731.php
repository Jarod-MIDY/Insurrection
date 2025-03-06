<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306170731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character ADD dying_scene_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character ADD is_dead BOOLEAN NOT NULL DEFAULT FALSE');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB0342360F94D FOREIGN KEY (dying_scene_id) REFERENCES scene (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_937AB0342360F94D ON character (dying_scene_id)');
        $this->addSql('ALTER TABLE player ALTER ready_to_play DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB0342360F94D');
        $this->addSql('DROP INDEX IDX_937AB0342360F94D');
        $this->addSql('ALTER TABLE character DROP dying_scene_id');
        $this->addSql('ALTER TABLE character DROP is_dead');
        $this->addSql('ALTER TABLE player ALTER ready_to_play SET DEFAULT false');
    }
}
