<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210706112252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bill (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, the_order_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7A2119E33DA5256D (image_id), INDEX IDX_7A2119E3C416F85B (the_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, name_ar VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, cat_type VARCHAR(255) NOT NULL, position INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_64C19C13DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE complaints (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, the_order_id INT NOT NULL, title VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, status INT NOT NULL, INDEX IDX_A05AAF3A7E3C61F9 (owner_id), INDEX IDX_A05AAF3AC416F85B (the_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, driver_id INT NOT NULL, INDEX IDX_8A8E26E919EB6921 (client_id), INDEX IDX_8A8E26E9C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, value INT NOT NULL, expire_at DATETIME NOT NULL, created_at DATETIME DEFAULT NULL, is_fixed_number TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_object (id INT AUTO_INCREMENT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, conversation_id INT NOT NULL, content VARCHAR(255) NOT NULL, type INT NOT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307F9AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_BF5476CA7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, the_order_id INT NOT NULL, price NUMERIC(10, 2) NOT NULL, INDEX IDX_29D6873EC3423909 (driver_id), INDEX IDX_29D6873EC416F85B (the_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, driver_id INT DEFAULT NULL, coupon_id INT DEFAULT NULL, place_id INT DEFAULT NULL, drop_place_id INT DEFAULT NULL, conversation_id INT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, status INT NOT NULL, created_at DATETIME DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, INDEX IDX_F52993987E3C61F9 (owner_id), INDEX IDX_F5299398C3423909 (driver_id), INDEX IDX_F529939866C5951B (coupon_id), UNIQUE INDEX UNIQ_F5299398DA6A219 (place_id), UNIQUE INDEX UNIQ_F5299398F945FD90 (drop_place_id), INDEX IDX_F52993989AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, the_order_id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_741D53CDC416F85B (the_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, the_order_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, INDEX IDX_D34A04AD3DA5256D (image_id), INDEX IDX_D34A04ADC416F85B (the_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, reviewer_id INT NOT NULL, reviewed_id INT NOT NULL, the_order_id INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, stars INT NOT NULL, INDEX IDX_794381C670574616 (reviewer_id), INDEX IDX_794381C65254E55 (reviewed_id), INDEX IDX_794381C6C416F85B (the_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, logo_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, commission INT NOT NULL, terms_conditions VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9F74B898F98F144A (logo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slider (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, position INT NOT NULL, INDEX IDX_CFC710073DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, form_img_id INT DEFAULT NULL, license_img_id INT DEFAULT NULL, front_img_id INT DEFAULT NULL, back_img_id INT DEFAULT NULL, id_card_img_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, stcpay VARCHAR(255) NOT NULL, code INT DEFAULT NULL, account_status INT DEFAULT 2 NOT NULL, status_note VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, account_balance NUMERIC(10, 2) DEFAULT NULL, total_delivery_fees NUMERIC(10, 2) DEFAULT NULL, created_at DATETIME DEFAULT NULL, mobile_token VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, id_number VARCHAR(255) DEFAULT NULL, permissions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649444F97DD (phone), UNIQUE INDEX UNIQ_8D93D649187B3B00 (stcpay), INDEX IDX_8D93D6493DA5256D (image_id), INDEX IDX_8D93D649C2844BFB (form_img_id), INDEX IDX_8D93D649BA84F153 (license_img_id), INDEX IDX_8D93D6492296D9BC (front_img_id), INDEX IDX_8D93D6491140577F (back_img_id), INDEX IDX_8D93D649AA4701D6 (id_card_img_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E33DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E3C416F85B FOREIGN KEY (the_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C13DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE complaints ADD CONSTRAINT FK_A05AAF3A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE complaints ADD CONSTRAINT FK_A05AAF3AC416F85B FOREIGN KEY (the_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E919EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9C3423909 FOREIGN KEY (driver_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EC3423909 FOREIGN KEY (driver_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EC416F85B FOREIGN KEY (the_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993987E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398C3423909 FOREIGN KEY (driver_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939866C5951B FOREIGN KEY (coupon_id) REFERENCES coupon (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398F945FD90 FOREIGN KEY (drop_place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CDC416F85B FOREIGN KEY (the_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD3DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC416F85B FOREIGN KEY (the_order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C670574616 FOREIGN KEY (reviewer_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C65254E55 FOREIGN KEY (reviewed_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6C416F85B FOREIGN KEY (the_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B898F98F144A FOREIGN KEY (logo_id) REFERENCES media_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slider ADD CONSTRAINT FK_CFC710073DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6493DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649C2844BFB FOREIGN KEY (form_img_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649BA84F153 FOREIGN KEY (license_img_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6492296D9BC FOREIGN KEY (front_img_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6491140577F FOREIGN KEY (back_img_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649AA4701D6 FOREIGN KEY (id_card_img_id) REFERENCES media_object (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989AC0396');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939866C5951B');
        $this->addSql('ALTER TABLE bill DROP FOREIGN KEY FK_7A2119E33DA5256D');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C13DA5256D');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD3DA5256D');
        $this->addSql('ALTER TABLE setting DROP FOREIGN KEY FK_9F74B898F98F144A');
        $this->addSql('ALTER TABLE slider DROP FOREIGN KEY FK_CFC710073DA5256D');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6493DA5256D');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649C2844BFB');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649BA84F153');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492296D9BC');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6491140577F');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649AA4701D6');
        $this->addSql('ALTER TABLE bill DROP FOREIGN KEY FK_7A2119E3C416F85B');
        $this->addSql('ALTER TABLE complaints DROP FOREIGN KEY FK_A05AAF3AC416F85B');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EC416F85B');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CDC416F85B');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADC416F85B');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6C416F85B');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398DA6A219');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398F945FD90');
        $this->addSql('ALTER TABLE complaints DROP FOREIGN KEY FK_A05AAF3A7E3C61F9');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E919EB6921');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9C3423909');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA7E3C61F9');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EC3423909');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993987E3C61F9');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398C3423909');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C670574616');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C65254E55');
        $this->addSql('DROP TABLE bill');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE complaints');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE slider');
        $this->addSql('DROP TABLE `user`');
    }
}
