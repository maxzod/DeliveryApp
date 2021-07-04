<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210704100643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398DA6A219');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398F945FD90');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398F945FD90 FOREIGN KEY (drop_place_id) REFERENCES place (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADC416F85B');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC416F85B FOREIGN KEY (the_order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f5299398da6a219');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f5299398f945fd90');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f5299398da6a219 FOREIGN KEY (place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f5299398f945fd90 FOREIGN KEY (drop_place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04adc416f85b');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04adc416f85b FOREIGN KEY (the_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
