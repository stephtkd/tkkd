<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513152928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member DROP instructor, DROP bureau, CHANGE phone_number phone_number VARCHAR(50) DEFAULT NULL, CHANGE emergency_phone emergency_phone VARCHAR(55) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member ADD instructor TINYINT(1) NOT NULL, ADD bureau TINYINT(1) NOT NULL, CHANGE phone_number phone_number VARCHAR(50) NOT NULL, CHANGE emergency_phone emergency_phone VARCHAR(55) NOT NULL');
    }
}
