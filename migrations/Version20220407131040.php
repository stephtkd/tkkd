<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220407131040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_picture_images CHANGE image image_album VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE category_album DROP FOREIGN KEY FK_D1AB210AE645AE9A');
        $this->addSql('DROP INDEX IDX_D1AB210AE645AE9A ON category_album');
        $this->addSql('ALTER TABLE category_album DROP album_picture_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_picture_images CHANGE image_album image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE category_album ADD album_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category_album ADD CONSTRAINT FK_D1AB210AE645AE9A FOREIGN KEY (album_picture_id) REFERENCES album_picture (id)');
        $this->addSql('CREATE INDEX IDX_D1AB210AE645AE9A ON category_album (album_picture_id)');
    }
}
