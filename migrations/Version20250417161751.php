<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417161751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE scene ADD finished_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scene DROP real_duration
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scene ALTER ready_logs DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN scene.finished_at IS '(DC2Type:datetime_immutable)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scene ADD real_duration INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scene DROP finished_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scene ALTER ready_logs SET DEFAULT '[]'
        SQL);
    }
}
