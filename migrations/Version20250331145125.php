<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331145125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scene ADD leader_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scene ADD CONSTRAINT FK_D979EFDA73154ED4 FOREIGN KEY (leader_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D979EFDA73154ED4 ON scene (leader_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE scene DROP CONSTRAINT FK_D979EFDA73154ED4');
        $this->addSql('DROP INDEX IDX_D979EFDA73154ED4');
        $this->addSql('ALTER TABLE scene DROP leader_id');
    }
}
