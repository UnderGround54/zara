<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319134132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(100) NOT NULL, status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_authorization (id INT AUTO_INCREMENT NOT NULL, profil_id INT NOT NULL, label VARCHAR(255) NOT NULL, details JSON DEFAULT NULL, INDEX IDX_EE7F3614275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `right` (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(100) NOT NULL, status TINYINT(1) NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE right_profil (right_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_E62E580C54976835 (right_id), INDEX IDX_E62E580C275ED078 (profil_id), PRIMARY KEY(right_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profil (user_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_8384A9AAA76ED395 (user_id), INDEX IDX_8384A9AA275ED078 (profil_id), PRIMARY KEY(user_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_right (user_id INT NOT NULL, right_id INT NOT NULL, INDEX IDX_56088E4CA76ED395 (user_id), INDEX IDX_56088E4C54976835 (right_id), PRIMARY KEY(user_id, right_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profil_authorization ADD CONSTRAINT FK_EE7F3614275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE right_profil ADD CONSTRAINT FK_E62E580C54976835 FOREIGN KEY (right_id) REFERENCES `right` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE right_profil ADD CONSTRAINT FK_E62E580C275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profil ADD CONSTRAINT FK_8384A9AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profil ADD CONSTRAINT FK_8384A9AA275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_right ADD CONSTRAINT FK_56088E4CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_right ADD CONSTRAINT FK_56088E4C54976835 FOREIGN KEY (right_id) REFERENCES `right` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_authorization DROP FOREIGN KEY FK_EE7F3614275ED078');
        $this->addSql('ALTER TABLE right_profil DROP FOREIGN KEY FK_E62E580C54976835');
        $this->addSql('ALTER TABLE right_profil DROP FOREIGN KEY FK_E62E580C275ED078');
        $this->addSql('ALTER TABLE user_profil DROP FOREIGN KEY FK_8384A9AAA76ED395');
        $this->addSql('ALTER TABLE user_profil DROP FOREIGN KEY FK_8384A9AA275ED078');
        $this->addSql('ALTER TABLE user_right DROP FOREIGN KEY FK_56088E4CA76ED395');
        $this->addSql('ALTER TABLE user_right DROP FOREIGN KEY FK_56088E4C54976835');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profil_authorization');
        $this->addSql('DROP TABLE `right`');
        $this->addSql('DROP TABLE right_profil');
        $this->addSql('DROP TABLE user_profil');
        $this->addSql('DROP TABLE user_right');
    }
}
