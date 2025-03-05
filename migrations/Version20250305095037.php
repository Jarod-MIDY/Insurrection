<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305095037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP number_of_players');
        $this->addSql('ALTER TABLE game ALTER max_players DROP DEFAULT');
        $this->addSql('ALTER TABLE player RENAME COLUMN roles TO role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE player RENAME COLUMN role TO roles');
        $this->addSql('ALTER TABLE game ADD number_of_players INT DEFAULT 5 NOT NULL');
        $this->addSql('ALTER TABLE game ALTER max_players SET DEFAULT 5');
    }
}
