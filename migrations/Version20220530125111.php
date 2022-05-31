<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530125111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_subscription ADD medical_certificate_name VARCHAR(255) DEFAULT NULL, ADD comment VARCHAR(5000) DEFAULT NULL');
        $this->addSql('ALTER TABLE member DROP medical_certificate_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_subscription DROP medical_certificate_name, DROP comment');
        $this->addSql('ALTER TABLE member ADD medical_certificate_name VARCHAR(255) DEFAULT NULL');
    }
}
