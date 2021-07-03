<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210703140126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE bill_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE complaints_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE coupon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE offer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE place_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE setting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE slider_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bill (id INT NOT NULL, image_id INT DEFAULT NULL, the_order_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7A2119E33DA5256D ON bill (image_id)');
        $this->addSql('CREATE INDEX IDX_7A2119E3C416F85B ON bill (the_order_id)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name_ar VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, cat_type VARCHAR(255) NOT NULL, position INT NOT NULL, image VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE complaints (id INT NOT NULL, owner_id INT DEFAULT NULL, the_order_id INT NOT NULL, title VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, status INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A05AAF3A7E3C61F9 ON complaints (owner_id)');
        $this->addSql('CREATE INDEX IDX_A05AAF3AC416F85B ON complaints (the_order_id)');
        $this->addSql('CREATE TABLE conversation (id INT NOT NULL, client_id INT NOT NULL, driver_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A8E26E919EB6921 ON conversation (client_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9C3423909 ON conversation (driver_id)');
        $this->addSql('CREATE TABLE coupon (id INT NOT NULL, code VARCHAR(255) NOT NULL, value INT NOT NULL, expire_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_fixed_number BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media_object (id INT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, sender_id INT NOT NULL, conversation_id INT NOT NULL, content VARCHAR(255) NOT NULL, type INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F9AC0396 ON message (conversation_id)');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5476CA7E3C61F9 ON notification (owner_id)');
        $this->addSql('CREATE TABLE offer (id INT NOT NULL, driver_id INT DEFAULT NULL, the_order_id INT NOT NULL, price NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_29D6873EC3423909 ON offer (driver_id)');
        $this->addSql('CREATE INDEX IDX_29D6873EC416F85B ON offer (the_order_id)');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, owner_id INT DEFAULT NULL, driver_id INT DEFAULT NULL, coupon_id INT DEFAULT NULL, place_id INT DEFAULT NULL, drop_place_id INT DEFAULT NULL, conversation_id INT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F52993987E3C61F9 ON "order" (owner_id)');
        $this->addSql('CREATE INDEX IDX_F5299398C3423909 ON "order" (driver_id)');
        $this->addSql('CREATE INDEX IDX_F529939866C5951B ON "order" (coupon_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398DA6A219 ON "order" (place_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398F945FD90 ON "order" (drop_place_id)');
        $this->addSql('CREATE INDEX IDX_F52993989AC0396 ON "order" (conversation_id)');
        $this->addSql('CREATE TABLE place (id INT NOT NULL, the_order_id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_741D53CDC416F85B ON place (the_order_id)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, image_id INT DEFAULT NULL, the_order_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04AD3DA5256D ON product (image_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADC416F85B ON product (the_order_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, reviewer_id INT NOT NULL, reviewed_id INT NOT NULL, the_order_id INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, stars INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C670574616 ON review (reviewer_id)');
        $this->addSql('CREATE INDEX IDX_794381C65254E55 ON review (reviewed_id)');
        $this->addSql('CREATE INDEX IDX_794381C6C416F85B ON review (the_order_id)');
        $this->addSql('CREATE TABLE setting (id INT NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, commission INT NOT NULL, terms_conditions VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE slider (id INT NOT NULL, image VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, image_id INT DEFAULT NULL, form_img_id INT DEFAULT NULL, license_img_id INT DEFAULT NULL, front_img_id INT DEFAULT NULL, back_img_id INT DEFAULT NULL, id_card_img_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, stcpay VARCHAR(255) NOT NULL, code INT DEFAULT NULL, account_status INT DEFAULT 2 NOT NULL, status_note VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, account_balance NUMERIC(10, 2) DEFAULT NULL, total_delivery_fees NUMERIC(10, 2) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, mobile_token VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, id_number VARCHAR(255) DEFAULT NULL, permissions TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649444F97DD ON "user" (phone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649187B3B00 ON "user" (stcpay)');
        $this->addSql('CREATE INDEX IDX_8D93D6493DA5256D ON "user" (image_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649C2844BFB ON "user" (form_img_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649BA84F153 ON "user" (license_img_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6492296D9BC ON "user" (front_img_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491140577F ON "user" (back_img_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AA4701D6 ON "user" (id_card_img_id)');
        $this->addSql('COMMENT ON COLUMN "user".permissions IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E33DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E3C416F85B FOREIGN KEY (the_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaints ADD CONSTRAINT FK_A05AAF3A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaints ADD CONSTRAINT FK_A05AAF3AC416F85B FOREIGN KEY (the_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E919EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9C3423909 FOREIGN KEY (driver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EC3423909 FOREIGN KEY (driver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EC416F85B FOREIGN KEY (the_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993987E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398C3423909 FOREIGN KEY (driver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F529939866C5951B FOREIGN KEY (coupon_id) REFERENCES coupon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398F945FD90 FOREIGN KEY (drop_place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993989AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CDC416F85B FOREIGN KEY (the_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD3DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC416F85B FOREIGN KEY (the_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C670574616 FOREIGN KEY (reviewer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C65254E55 FOREIGN KEY (reviewed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6C416F85B FOREIGN KEY (the_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6493DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649C2844BFB FOREIGN KEY (form_img_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649BA84F153 FOREIGN KEY (license_img_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6492296D9BC FOREIGN KEY (front_img_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6491140577F FOREIGN KEY (back_img_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649AA4701D6 FOREIGN KEY (id_card_img_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993989AC0396');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F529939866C5951B');
        $this->addSql('ALTER TABLE bill DROP CONSTRAINT FK_7A2119E33DA5256D');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD3DA5256D');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6493DA5256D');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649C2844BFB');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649BA84F153');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6492296D9BC');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6491140577F');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649AA4701D6');
        $this->addSql('ALTER TABLE bill DROP CONSTRAINT FK_7A2119E3C416F85B');
        $this->addSql('ALTER TABLE complaints DROP CONSTRAINT FK_A05AAF3AC416F85B');
        $this->addSql('ALTER TABLE offer DROP CONSTRAINT FK_29D6873EC416F85B');
        $this->addSql('ALTER TABLE place DROP CONSTRAINT FK_741D53CDC416F85B');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADC416F85B');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6C416F85B');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398DA6A219');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398F945FD90');
        $this->addSql('ALTER TABLE complaints DROP CONSTRAINT FK_A05AAF3A7E3C61F9');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E919EB6921');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9C3423909');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA7E3C61F9');
        $this->addSql('ALTER TABLE offer DROP CONSTRAINT FK_29D6873EC3423909');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993987E3C61F9');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398C3423909');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C670574616');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C65254E55');
        $this->addSql('DROP SEQUENCE bill_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE complaints_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE coupon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_object_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE offer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE place_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE setting_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE slider_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE bill');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE complaints');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE slider');
        $this->addSql('DROP TABLE "user"');
    }
}
