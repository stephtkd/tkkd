<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220502082552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member ADD membership_rate_id INT DEFAULT NULL, ADD user_id INT NOT NULL, DROP user');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78353409CE FOREIGN KEY (membership_rate_id) REFERENCES membership_rate (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78353409CE ON member (membership_rate_id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78A76ED395 ON member (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78353409CE');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78A76ED395');
        $this->addSql('DROP INDEX IDX_70E4FA78353409CE ON member');
        $this->addSql('DROP INDEX IDX_70E4FA78A76ED395 ON member');
        $this->addSql('ALTER TABLE member ADD user VARCHAR(255) DEFAULT NULL, DROP membership_rate_id, DROP user_id');
    }
}