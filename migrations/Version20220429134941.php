<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429134941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE criteria DROP FOREIGN KEY FK_B61F9B81990BEA15');
        $this->addSql('DROP INDEX IDX_B61F9B81990BEA15 ON criteria');
        $this->addSql('ALTER TABLE criteria DROP criteria_id');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA771F7E88B');
        $this->addSql('DROP INDEX IDX_3BAE0AA771F7E88B ON event');
        $this->addSql('ALTER TABLE event DROP event_id');
        $this->addSql('ALTER TABLE rate ADD rate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F39BC999F9F FOREIGN KEY (rate_id) REFERENCES criteria (id)');
        $this->addSql('CREATE INDEX IDX_DFEC3F39BC999F9F ON rate (rate_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE criteria ADD criteria_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE criteria ADD CONSTRAINT FK_B61F9B81990BEA15 FOREIGN KEY (criteria_id) REFERENCES rate (id)');
        $this->addSql('CREATE INDEX IDX_B61F9B81990BEA15 ON criteria (criteria_id)');
        $this->addSql('ALTER TABLE event ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA771F7E88B FOREIGN KEY (event_id) REFERENCES rate (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA771F7E88B ON event (event_id)');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F39BC999F9F');
        $this->addSql('DROP INDEX IDX_DFEC3F39BC999F9F ON rate');
        $this->addSql('ALTER TABLE rate DROP rate_id');
    }
}
