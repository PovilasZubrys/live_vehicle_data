<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321102545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gps DROP FOREIGN KEY FK_54EA5DC71DEB1EBB');
        $this->addSql('DROP INDEX IDX_54EA5DC71DEB1EBB ON gps');
        $this->addSql('ALTER TABLE gps DROP vehicle_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gps ADD vehicle_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE gps ADD CONSTRAINT FK_54EA5DC71DEB1EBB FOREIGN KEY (vehicle_id_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX IDX_54EA5DC71DEB1EBB ON gps (vehicle_id_id)');
    }
}
