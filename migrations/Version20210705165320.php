<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705165320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting ADD logo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE setting DROP logo');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B898F98F144A FOREIGN KEY (logo_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9F74B898F98F144A ON setting (logo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE setting DROP CONSTRAINT FK_9F74B898F98F144A');
        $this->addSql('DROP INDEX IDX_9F74B898F98F144A');
        $this->addSql('ALTER TABLE setting ADD logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE setting DROP logo_id');
    }
}
