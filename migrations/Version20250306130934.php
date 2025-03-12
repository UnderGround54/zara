<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306130934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE device_type (id INT AUTO_INCREMENT NOT NULL, device_name VARCHAR(255) NOT NULL, device_id INT NOT NULL, normal_price VARCHAR(255) NOT NULL, imported_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eligibility (id INT AUTO_INCREMENT NOT NULL, msisdn VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, arpu VARCHAR(255) NOT NULL, is_smartphone TINYINT(1) NOT NULL, eligible_device VARCHAR(255) NOT NULL, current_network VARCHAR(255) NOT NULL, data_activity VARCHAR(255) NOT NULL, discount_rate VARCHAR(255) NOT NULL, cin VARCHAR(255) NOT NULL, is_old_sim TINYINT(1) NOT NULL, is_purchase TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', imported_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_history (id INT AUTO_INCREMENT NOT NULL, selected_device_type_id INT NOT NULL, msisdn VARCHAR(15) NOT NULL, idbill_s3 VARCHAR(255) NOT NULL, idpayment VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, selected_device_id VARCHAR(255) NOT NULL, discount_rate DOUBLE PRECISION NOT NULL, imei VARCHAR(255) NOT NULL, payment_amount VARCHAR(255) NOT NULL, payment_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3C60BA327F9DA380 (selected_device_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_history ADD CONSTRAINT FK_3C60BA327F9DA380 FOREIGN KEY (selected_device_type_id) REFERENCES device_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_history DROP FOREIGN KEY FK_3C60BA327F9DA380');
        $this->addSql('DROP TABLE device_type');
        $this->addSql('DROP TABLE eligibility');
        $this->addSql('DROP TABLE purchase_history');
    }
}
