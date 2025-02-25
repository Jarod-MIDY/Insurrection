<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225150818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character (id SERIAL NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, features TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_937AB0347E3C61F9 ON character (owner_id)');
        $this->addSql('CREATE TABLE game (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, subject TEXT DEFAULT NULL, things_to_talk_about TEXT DEFAULT NULL, things_to_half_talk TEXT DEFAULT NULL, baned_topics TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE influence_token (id SERIAL NOT NULL, player_id INT NOT NULL, linked_role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E8AAEC6599E6F5DF ON influence_token (player_id)');
        $this->addSql('CREATE TABLE player (id SERIAL NOT NULL, linked_user_id INT NOT NULL, game_id INT NOT NULL, roles VARCHAR(255) NOT NULL, informations JSON NOT NULL, notes TEXT DEFAULT NULL, radiance_token INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98197A65CC26EB02 ON player (linked_user_id)');
        $this->addSql('CREATE INDEX IDX_98197A65E48FD905 ON player (game_id)');
        $this->addSql('CREATE TABLE scene (id SERIAL NOT NULL, game_id INT NOT NULL, estimated_duration VARCHAR(255) DEFAULT NULL, real_duration VARCHAR(255) DEFAULT NULL, goal TEXT DEFAULT NULL, story TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D979EFDAE48FD905 ON scene (game_id)');
        $this->addSql('COMMENT ON COLUMN scene.estimated_duration IS \'(DC2Type:dateinterval)\'');
        $this->addSql('COMMENT ON COLUMN scene.real_duration IS \'(DC2Type:dateinterval)\'');
        $this->addSql('CREATE TABLE scene_character (scene_id INT NOT NULL, character_id INT NOT NULL, PRIMARY KEY(scene_id, character_id))');
        $this->addSql('CREATE INDEX IDX_FE1E8CBE166053B4 ON scene_character (scene_id)');
        $this->addSql('CREATE INDEX IDX_FE1E8CBE1136BE75 ON scene_character (character_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "user" (username)');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB0347E3C61F9 FOREIGN KEY (owner_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE influence_token ADD CONSTRAINT FK_E8AAEC6599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65CC26EB02 FOREIGN KEY (linked_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scene ADD CONSTRAINT FK_D979EFDAE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scene_character ADD CONSTRAINT FK_FE1E8CBE166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scene_character ADD CONSTRAINT FK_FE1E8CBE1136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB0347E3C61F9');
        $this->addSql('ALTER TABLE influence_token DROP CONSTRAINT FK_E8AAEC6599E6F5DF');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A65CC26EB02');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A65E48FD905');
        $this->addSql('ALTER TABLE scene DROP CONSTRAINT FK_D979EFDAE48FD905');
        $this->addSql('ALTER TABLE scene_character DROP CONSTRAINT FK_FE1E8CBE166053B4');
        $this->addSql('ALTER TABLE scene_character DROP CONSTRAINT FK_FE1E8CBE1136BE75');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE influence_token');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE scene');
        $this->addSql('DROP TABLE scene_character');
        $this->addSql('DROP TABLE "user"');
    }
}
