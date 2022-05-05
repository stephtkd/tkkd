<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505061139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rate_event (rate_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_ABEE1384BC999F9F (rate_id), INDEX IDX_ABEE138471F7E88B (event_id), PRIMARY KEY(rate_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rate_event ADD CONSTRAINT FK_ABEE1384BC999F9F FOREIGN KEY (rate_id) REFERENCES rate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rate_event ADD CONSTRAINT FK_ABEE138471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE rate_event');
    }
}
