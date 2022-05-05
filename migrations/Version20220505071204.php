<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505071204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78241851A');
        $this->addSql('DROP INDEX IDX_70E4FA78241851A ON member');
        $this->addSql('ALTER TABLE member DROP responsible_adult_id, CHANGE up_to_date_membership up_to_date_membership TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member ADD responsible_adult_id INT NOT NULL, CHANGE up_to_date_membership up_to_date_membership TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78241851A FOREIGN KEY (responsible_adult_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78241851A ON member (responsible_adult_id)');
    }
}
