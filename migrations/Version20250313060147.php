<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313060147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_history DROP FOREIGN KEY FK_3C60BA327F9DA380');
        $this->addSql('DROP INDEX IDX_3C60BA327F9DA380 ON purchase_history');
        $this->addSql('ALTER TABLE purchase_history ADD selected_device_type VARCHAR(255) DEFAULT NULL, DROP selected_device_type_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_history ADD selected_device_type_id INT NOT NULL, DROP selected_device_type');
        $this->addSql('ALTER TABLE purchase_history ADD CONSTRAINT FK_3C60BA327F9DA380 FOREIGN KEY (selected_device_type_id) REFERENCES device_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3C60BA327F9DA380 ON purchase_history (selected_device_type_id)');
    }
}
