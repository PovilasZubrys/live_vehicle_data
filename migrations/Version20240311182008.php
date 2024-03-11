<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240311182008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E48694A4C7D4');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E48694A4C7D4 FOREIGN KEY (device_id) REFERENCES vehicle (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E48694A4C7D4');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E48694A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
    }
}
