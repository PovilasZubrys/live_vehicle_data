<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304210719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rpm ADD vehicle_id INT NOT NULL');
        $this->addSql('ALTER TABLE rpm ADD CONSTRAINT FK_B408013F545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX IDX_B408013F545317D1 ON rpm (vehicle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rpm DROP FOREIGN KEY FK_B408013F545317D1');
        $this->addSql('DROP INDEX IDX_B408013F545317D1 ON rpm');
        $this->addSql('ALTER TABLE rpm DROP vehicle_id');
    }
}
