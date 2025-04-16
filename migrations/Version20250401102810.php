<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401102810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scene DROP COLUMN estimated_duration');
        $this->addSql('ALTER TABLE scene DROP real_duration');
        $this->addSql('ALTER TABLE scene ADD COLUMN estimated_duration INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scene ADD COLUMN real_duration INT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN scene.estimated_duration IS NULL');
        $this->addSql('COMMENT ON COLUMN scene.real_duration IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE scene ALTER estimated_duration TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE scene ALTER real_duration TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN scene.estimated_duration IS \'(DC2Type:dateinterval)\'');
        $this->addSql('COMMENT ON COLUMN scene.real_duration IS \'(DC2Type:dateinterval)\'');
    }
}
