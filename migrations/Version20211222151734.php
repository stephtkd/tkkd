<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222151734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, maximum_number_of_participants INT DEFAULT NULL, adult_rate DOUBLE PRECISION DEFAULT NULL, child_rate DOUBLE PRECISION DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, registration_deadline DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_member (event_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_427D8D2A71F7E88B (event_id), INDEX IDX_427D8D2A7597D3FE (member_id), PRIMARY KEY(event_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, responsible_adult_id INT NOT NULL, membership_rate_id INT DEFAULT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, sex VARCHAR(10) NOT NULL, birthdate DATE NOT NULL, email VARCHAR(255) NOT NULL, street_adress VARCHAR(255) NOT NULL, postal_code VARCHAR(55) NOT NULL, city VARCHAR(100) NOT NULL, nationality VARCHAR(100) NOT NULL, phone_number VARCHAR(50) NOT NULL, comment VARCHAR(255) DEFAULT NULL, level VARCHAR(55) DEFAULT NULL, suscription_date DATE DEFAULT NULL, emergency_phone VARCHAR(55) NOT NULL, up_to_date_membership TINYINT(1) NOT NULL, status VARCHAR(55) DEFAULT NULL, membership_state VARCHAR(55) DEFAULT NULL, photo_name VARCHAR(255) DEFAULT NULL, medical_certificate_name VARCHAR(255) DEFAULT NULL, INDEX IDX_70E4FA78241851A (responsible_adult_id), INDEX IDX_70E4FA78353409CE (membership_rate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membership (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, membership_rate_id INT NOT NULL, season_year INT DEFAULT NULL, suscription_date DATETIME DEFAULT NULL, membership_up_to_date TINYINT(1) NOT NULL, membership_state VARCHAR(55) NOT NULL, INDEX IDX_86FFD2857597D3FE (member_id), INDEX IDX_86FFD285353409CE (membership_rate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membership_rate (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(55) NOT NULL, price DOUBLE PRECISION NOT NULL, maximum_age INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(55) NOT NULL, text VARCHAR(255) NOT NULL, publication_date DATETIME NOT NULL, image_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_member ADD CONSTRAINT FK_427D8D2A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_member ADD CONSTRAINT FK_427D8D2A7597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78241851A FOREIGN KEY (responsible_adult_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78353409CE FOREIGN KEY (membership_rate_id) REFERENCES membership_rate (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD2857597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285353409CE FOREIGN KEY (membership_rate_id) REFERENCES membership_rate (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_member DROP FOREIGN KEY FK_427D8D2A71F7E88B');
        $this->addSql('ALTER TABLE event_member DROP FOREIGN KEY FK_427D8D2A7597D3FE');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD2857597D3FE');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78353409CE');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285353409CE');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78241851A');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_member');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE membership');
        $this->addSql('DROP TABLE membership_rate');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
    }
}
