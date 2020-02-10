<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200210165139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE purchase_order (id INT AUTO_INCREMENT NOT NULL, front_user_id INT NOT NULL, purchase_date DATETIME DEFAULT NULL, purchase_total_amount DOUBLE PRECISION NOT NULL, payment_mode TINYINT(1) NOT NULL, status INT NOT NULL, INDEX IDX_21E210B27E5A750F (front_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, purchase_order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, order_amount DOUBLE PRECISION NOT NULL, delivery_info VARCHAR(255) DEFAULT NULL, INDEX IDX_2530ADE6A45D7E6A (purchase_order_id), INDEX IDX_2530ADE64584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, document_id INT NOT NULL, send_mode_id INT NOT NULL, type VARCHAR(255) NOT NULL, amount_by_page DOUBLE PRECISION DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_D34A04ADC33F7837 (document_id), INDEX IDX_D34A04ADA1ACF024 (send_mode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE front_user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, is_active TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, username VARCHAR(50) NOT NULL, amount_coins DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_B5436C25F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, file VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, pages INT DEFAULT NULL, date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE send_mode (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B27E5A750F FOREIGN KEY (front_user_id) REFERENCES front_user (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE6A45D7E6A FOREIGN KEY (purchase_order_id) REFERENCES purchase_order (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA1ACF024 FOREIGN KEY (send_mode_id) REFERENCES send_mode (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE6A45D7E6A');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE64584665A');
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B27E5A750F');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADC33F7837');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA1ACF024');
        $this->addSql('DROP TABLE purchase_order');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE front_user');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE send_mode');
    }
}
