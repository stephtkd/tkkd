<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513152249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78A76ED395');
        $this->addSql('DROP INDEX IDX_70E4FA78A76ED395 ON member');
        $this->addSql('ALTER TABLE member DROP responsible_adult, CHANGE user_id responsible_adult_id INT NOT NULL');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78241851A FOREIGN KEY (responsible_adult_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78241851A ON member (responsible_adult_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78241851A');
        $this->addSql('DROP INDEX IDX_70E4FA78241851A ON member');
        $this->addSql('ALTER TABLE member ADD responsible_adult VARCHAR(255) DEFAULT NULL, CHANGE responsible_adult_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78A76ED395 ON member (user_id)');
    }
}
