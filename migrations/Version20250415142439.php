<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415142439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE character ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE influence_token DROP CONSTRAINT fk_e8aaec6599e6f5df');
        $this->addSql('DROP INDEX idx_e8aaec6599e6f5df');
        $this->addSql('ALTER TABLE influence_token ADD sender_id INT NOT NULL');
        $this->addSql('ALTER TABLE influence_token ADD is_used BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE influence_token ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE influence_token ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE influence_token RENAME COLUMN player_id TO receiver_id');
        $this->addSql('ALTER TABLE influence_token ADD CONSTRAINT FK_E8AAEC65CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE influence_token ADD CONSTRAINT FK_E8AAEC65F624B39D FOREIGN KEY (sender_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E8AAEC65CD53EDB6 ON influence_token (receiver_id)');
        $this->addSql('CREATE INDEX IDX_E8AAEC65F624B39D ON influence_token (sender_id)');
        $this->addSql('ALTER TABLE player ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE player ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE scene ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE scene ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE scene_story ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE scene_story ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE token_action ADD influence_token_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE token_action ADD CONSTRAINT FK_46B42390EC953A05 FOREIGN KEY (influence_token_id) REFERENCES influence_token (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_46B42390EC953A05 ON token_action (influence_token_id)');
        $this->addSql('ALTER TABLE "user" ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER updated_at DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE player ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE player ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE "user" ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE influence_token DROP CONSTRAINT FK_E8AAEC65CD53EDB6');
        $this->addSql('ALTER TABLE influence_token DROP CONSTRAINT FK_E8AAEC65F624B39D');
        $this->addSql('DROP INDEX IDX_E8AAEC65CD53EDB6');
        $this->addSql('DROP INDEX IDX_E8AAEC65F624B39D');
        $this->addSql('ALTER TABLE influence_token ADD player_id INT NOT NULL');
        $this->addSql('ALTER TABLE influence_token DROP receiver_id');
        $this->addSql('ALTER TABLE influence_token DROP sender_id');
        $this->addSql('ALTER TABLE influence_token DROP is_used');
        $this->addSql('ALTER TABLE influence_token ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE influence_token ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE influence_token ADD CONSTRAINT fk_e8aaec6599e6f5df FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e8aaec6599e6f5df ON influence_token (player_id)');
        $this->addSql('ALTER TABLE token_action DROP CONSTRAINT FK_46B42390EC953A05');
        $this->addSql('DROP INDEX UNIQ_46B42390EC953A05');
        $this->addSql('ALTER TABLE token_action DROP influence_token_id');
        $this->addSql('ALTER TABLE scene ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE scene ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE scene_story ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE scene_story ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE character ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE character ALTER updated_at SET DEFAULT \'now()\'');
    }
}
