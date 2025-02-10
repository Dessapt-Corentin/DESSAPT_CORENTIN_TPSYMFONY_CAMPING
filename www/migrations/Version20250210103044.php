<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210103044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accommodation (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, label VARCHAR(50) NOT NULL, location_number VARCHAR(50) NOT NULL, size INT NOT NULL, description LONGTEXT NOT NULL, capacity INT NOT NULL, image VARCHAR(255) NOT NULL, availability TINYINT(1) NOT NULL, INDEX IDX_2D385412C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pricing (id INT AUTO_INCREMENT NOT NULL, accommodation_id INT NOT NULL, season_id INT NOT NULL, price NUMERIC(8, 2) NOT NULL, INDEX IDX_E5F1AC338F3692CD (accommodation_id), INDEX IDX_E5F1AC334EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, accommodation_id INT DEFAULT NULL, adult_number INT NOT NULL, child_number INT NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, INDEX IDX_1619C27DA76ED395 (user_id), INDEX IDX_1619C27D8F3692CD (accommodation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_accommodation (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(75) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, phone_number VARCHAR(20) NOT NULL, address VARCHAR(150) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accommodation ADD CONSTRAINT FK_2D385412C54C8C93 FOREIGN KEY (type_id) REFERENCES type_accommodation (id)');
        $this->addSql('ALTER TABLE pricing ADD CONSTRAINT FK_E5F1AC338F3692CD FOREIGN KEY (accommodation_id) REFERENCES accommodation (id)');
        $this->addSql('ALTER TABLE pricing ADD CONSTRAINT FK_E5F1AC334EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27D8F3692CD FOREIGN KEY (accommodation_id) REFERENCES accommodation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accommodation DROP FOREIGN KEY FK_2D385412C54C8C93');
        $this->addSql('ALTER TABLE pricing DROP FOREIGN KEY FK_E5F1AC338F3692CD');
        $this->addSql('ALTER TABLE pricing DROP FOREIGN KEY FK_E5F1AC334EC001D1');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27DA76ED395');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27D8F3692CD');
        $this->addSql('DROP TABLE accommodation');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE pricing');
        $this->addSql('DROP TABLE rental');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE type_accommodation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
