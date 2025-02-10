<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210130734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accommodation_equipment (accommodation_id INT NOT NULL, equipment_id INT NOT NULL, INDEX IDX_6A0325A58F3692CD (accommodation_id), INDEX IDX_6A0325A5517FE9FE (equipment_id), PRIMARY KEY(accommodation_id, equipment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accommodation_equipment ADD CONSTRAINT FK_6A0325A58F3692CD FOREIGN KEY (accommodation_id) REFERENCES accommodation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE accommodation_equipment ADD CONSTRAINT FK_6A0325A5517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rental CHANGE accommodation_id accommodation_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accommodation_equipment DROP FOREIGN KEY FK_6A0325A58F3692CD');
        $this->addSql('ALTER TABLE accommodation_equipment DROP FOREIGN KEY FK_6A0325A5517FE9FE');
        $this->addSql('DROP TABLE accommodation_equipment');
        $this->addSql('ALTER TABLE rental CHANGE accommodation_id accommodation_id INT DEFAULT NULL');
    }
}
