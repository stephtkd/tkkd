<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429135657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE criteria_rate');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE criteria_rate (criteria_id INT NOT NULL, rate_id INT NOT NULL, INDEX IDX_C1EB5DD6BC999F9F (rate_id), INDEX IDX_C1EB5DD6990BEA15 (criteria_id), PRIMARY KEY(criteria_id, rate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE criteria_rate ADD CONSTRAINT FK_C1EB5DD6BC999F9F FOREIGN KEY (rate_id) REFERENCES rate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE criteria_rate ADD CONSTRAINT FK_C1EB5DD6990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) ON DELETE CASCADE');
    }
}
