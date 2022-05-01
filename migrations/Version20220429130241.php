<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429130241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_picture DROP FOREIGN KEY FK_430C129BA64D2FB4');
        $this->addSql('DROP TABLE category_album');
        $this->addSql('DROP INDEX IDX_430C129BA64D2FB4 ON album_picture');
        $this->addSql('ALTER TABLE album_picture DROP category_album_id, DROP updated_at, CHANGE picture picture VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD season VARCHAR(55) DEFAULT NULL, ADD registration_open_date DATETIME DEFAULT NULL, DROP adult_rate, DROP child_rate, DROP price');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78A76ED395');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78353409CE');
        $this->addSql('DROP INDEX IDX_70E4FA78353409CE ON member');
        $this->addSql('DROP INDEX IDX_70E4FA78A76ED395 ON member');
        $this->addSql('ALTER TABLE member ADD instructor TINYINT(1) NOT NULL, ADD bureau TINYINT(1) NOT NULL, ADD responsible_adult VARCHAR(255) DEFAULT NULL, DROP membership_rate_id, DROP user_id, DROP suscription_date');
        $this->addSql('ALTER TABLE membership CHANGE membership_up_to_date membership_up_to_date TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE pictures_album CHANGE album_picture_id album_picture_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_album (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, color VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE album_picture ADD category_album_id INT DEFAULT NULL, ADD updated_at DATETIME NOT NULL, CHANGE picture picture VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE album_picture ADD CONSTRAINT FK_430C129BA64D2FB4 FOREIGN KEY (category_album_id) REFERENCES category_album (id)');
        $this->addSql('CREATE INDEX IDX_430C129BA64D2FB4 ON album_picture (category_album_id)');
        $this->addSql('ALTER TABLE event ADD adult_rate DOUBLE PRECISION DEFAULT NULL, ADD child_rate DOUBLE PRECISION DEFAULT NULL, ADD price INT NOT NULL, DROP season, DROP registration_open_date');
        $this->addSql('ALTER TABLE member ADD membership_rate_id INT DEFAULT NULL, ADD user_id INT NOT NULL, ADD suscription_date DATE DEFAULT NULL, DROP instructor, DROP bureau, DROP responsible_adult');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78353409CE FOREIGN KEY (membership_rate_id) REFERENCES membership_rate (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78353409CE ON member (membership_rate_id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78A76ED395 ON member (user_id)');
        $this->addSql('ALTER TABLE membership CHANGE membership_up_to_date membership_up_to_date TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE pictures_album CHANGE album_picture_id album_picture_id INT NOT NULL');
    }
}
