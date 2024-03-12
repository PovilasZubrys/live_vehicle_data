<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240312232310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE engine_load ADD vehicle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE engine_load ADD CONSTRAINT FK_4F3D22E2545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX IDX_4F3D22E2545317D1 ON engine_load (vehicle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE engine_load DROP FOREIGN KEY FK_4F3D22E2545317D1');
        $this->addSql('DROP INDEX IDX_4F3D22E2545317D1 ON engine_load');
        $this->addSql('ALTER TABLE engine_load DROP vehicle_id');
    }
}
