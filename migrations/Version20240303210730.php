<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303210730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE speed ADD vehicle_id INT NOT NULL');
        $this->addSql('ALTER TABLE speed ADD CONSTRAINT FK_F26FEF6545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX IDX_F26FEF6545317D1 ON speed (vehicle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE speed DROP FOREIGN KEY FK_F26FEF6545317D1');
        $this->addSql('DROP INDEX IDX_F26FEF6545317D1 ON speed');
        $this->addSql('ALTER TABLE speed DROP vehicle_id');
    }
}
