<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303205313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absolute_load ADD vehicle_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE absolute_load ADD CONSTRAINT FK_FFAC384F1DEB1EBB FOREIGN KEY (vehicle_id_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FFAC384F1DEB1EBB ON absolute_load (vehicle_id_id)');
        $this->addSql('ALTER TABLE coolant_temp ADD vehicle_id INT NOT NULL');
        $this->addSql('ALTER TABLE coolant_temp ADD CONSTRAINT FK_BAA1EDA2545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BAA1EDA2545317D1 ON coolant_temp (vehicle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absolute_load DROP FOREIGN KEY FK_FFAC384F1DEB1EBB');
        $this->addSql('DROP INDEX UNIQ_FFAC384F1DEB1EBB ON absolute_load');
        $this->addSql('ALTER TABLE absolute_load DROP vehicle_id_id');
        $this->addSql('ALTER TABLE coolant_temp DROP FOREIGN KEY FK_BAA1EDA2545317D1');
        $this->addSql('DROP INDEX UNIQ_BAA1EDA2545317D1 ON coolant_temp');
        $this->addSql('ALTER TABLE coolant_temp DROP vehicle_id');
    }
}
