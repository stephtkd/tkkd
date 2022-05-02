<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220331141551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_picture CHANGE illustration image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE home_comment CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_picture CHANGE image illustration VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE home_comment CHANGE title title VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL');
    }
}
