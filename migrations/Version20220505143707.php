<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505143707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album_picture (id INT AUTO_INCREMENT NOT NULL, tag_id INT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_430C129BBAD26311 (tag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, relation VARCHAR(50) DEFAULT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, maximum_number_of_participants INT DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, season VARCHAR(55) DEFAULT NULL, registration_open_date DATETIME DEFAULT NULL, registration_deadline DATETIME DEFAULT NULL, link_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_member (event_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_427D8D2A71F7E88B (event_id), INDEX IDX_427D8D2A7597D3FE (member_id), PRIMARY KEY(event_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE home_comment (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, membership_rate_id INT DEFAULT NULL, user_id INT NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, sex VARCHAR(10) NOT NULL, birthdate DATE NOT NULL, email VARCHAR(255) NOT NULL, street_adress VARCHAR(255) NOT NULL, postal_code VARCHAR(55) NOT NULL, city VARCHAR(100) NOT NULL, nationality VARCHAR(100) NOT NULL, phone_number VARCHAR(50) NOT NULL, comment VARCHAR(255) DEFAULT NULL, level VARCHAR(55) DEFAULT NULL, instructor TINYINT(1) NOT NULL, bureau TINYINT(1) NOT NULL, emergency_phone VARCHAR(55) NOT NULL, up_to_date_membership TINYINT(1) NOT NULL, status VARCHAR(55) DEFAULT NULL, membership_state VARCHAR(55) DEFAULT NULL, photo_name VARCHAR(255) DEFAULT NULL, medical_certificate_name VARCHAR(255) DEFAULT NULL, responsible_adult VARCHAR(255) DEFAULT NULL, INDEX IDX_70E4FA78353409CE (membership_rate_id), INDEX IDX_70E4FA78A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membership (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, membership_rate_id INT NOT NULL, season_year INT DEFAULT NULL, suscription_date DATETIME DEFAULT NULL, membership_up_to_date TINYINT(1) DEFAULT NULL, membership_state VARCHAR(55) NOT NULL, INDEX IDX_86FFD2857597D3FE (member_id), INDEX IDX_86FFD285353409CE (membership_rate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membership_rate (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, maximum_age INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, delivery LONGTEXT NOT NULL, reference VARCHAR(255) NOT NULL, hello_asso_session_id VARCHAR(255) DEFAULT NULL, state INT NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_details (id INT AUTO_INCREMENT NOT NULL, my_order_id INT NOT NULL, subscription VARCHAR(255) NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_845CA2C1BFCDF877 (my_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pictures_album (id INT AUTO_INCREMENT NOT NULL, album_picture_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, images VARCHAR(255) NOT NULL, INDEX IDX_2A596727E645AE9A (album_picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, rate NUMERIC(10, 3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate_criteria (rate_id INT NOT NULL, criteria_id INT NOT NULL, INDEX IDX_61C88B20BC999F9F (rate_id), INDEX IDX_61C88B20990BEA15 (criteria_id), PRIMARY KEY(rate_id, criteria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate_event (rate_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_ABEE1384BC999F9F (rate_id), INDEX IDX_ABEE138471F7E88B (event_id), PRIMARY KEY(rate_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B9983CE5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slide_picture (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, illustration VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album_picture ADD CONSTRAINT FK_430C129BBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE event_member ADD CONSTRAINT FK_427D8D2A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_member ADD CONSTRAINT FK_427D8D2A7597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78353409CE FOREIGN KEY (membership_rate_id) REFERENCES membership_rate (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD2857597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285353409CE FOREIGN KEY (membership_rate_id) REFERENCES membership_rate (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1BFCDF877 FOREIGN KEY (my_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE pictures_album ADD CONSTRAINT FK_2A596727E645AE9A FOREIGN KEY (album_picture_id) REFERENCES album_picture (id)');
        $this->addSql('ALTER TABLE rate_criteria ADD CONSTRAINT FK_61C88B20BC999F9F FOREIGN KEY (rate_id) REFERENCES rate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rate_criteria ADD CONSTRAINT FK_61C88B20990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rate_event ADD CONSTRAINT FK_ABEE1384BC999F9F FOREIGN KEY (rate_id) REFERENCES rate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rate_event ADD CONSTRAINT FK_ABEE138471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password ADD CONSTRAINT FK_B9983CE5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pictures_album DROP FOREIGN KEY FK_2A596727E645AE9A');
        $this->addSql('ALTER TABLE rate_criteria DROP FOREIGN KEY FK_61C88B20990BEA15');
        $this->addSql('ALTER TABLE event_member DROP FOREIGN KEY FK_427D8D2A71F7E88B');
        $this->addSql('ALTER TABLE rate_event DROP FOREIGN KEY FK_ABEE138471F7E88B');
        $this->addSql('ALTER TABLE event_member DROP FOREIGN KEY FK_427D8D2A7597D3FE');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD2857597D3FE');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78353409CE');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285353409CE');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1BFCDF877');
        $this->addSql('ALTER TABLE rate_criteria DROP FOREIGN KEY FK_61C88B20BC999F9F');
        $this->addSql('ALTER TABLE rate_event DROP FOREIGN KEY FK_ABEE1384BC999F9F');
        $this->addSql('ALTER TABLE album_picture DROP FOREIGN KEY FK_430C129BBAD26311');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE reset_password DROP FOREIGN KEY FK_B9983CE5A76ED395');
        $this->addSql('DROP TABLE album_picture');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE criteria');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_member');
        $this->addSql('DROP TABLE home_comment');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE membership');
        $this->addSql('DROP TABLE membership_rate');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP TABLE pictures_album');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE rate_criteria');
        $this->addSql('DROP TABLE rate_event');
        $this->addSql('DROP TABLE reset_password');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE slide_picture');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE `user`');
    }
}
