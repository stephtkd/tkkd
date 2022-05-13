<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513100759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rate_criteria DROP FOREIGN KEY FK_61C88B20990BEA15');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78353409CE');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285353409CE');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1BFCDF877');
        $this->addSql('ALTER TABLE rate_criteria DROP FOREIGN KEY FK_61C88B20BC999F9F');
        $this->addSql('ALTER TABLE rate_event DROP FOREIGN KEY FK_ABEE1384BC999F9F');
        $this->addSql('CREATE TABLE event_option (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(3000) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_681F77E271F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_rate (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(3000) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_C027739271F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_option ADD CONSTRAINT FK_681F77E271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_rate ADD CONSTRAINT FK_C027739271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('DROP TABLE criteria');
        $this->addSql('DROP TABLE membership_rate');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE rate_criteria');
        $this->addSql('DROP TABLE rate_event');
        $this->addSql('ALTER TABLE event ADD allow_visitors TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX IDX_70E4FA78353409CE ON member');
        $this->addSql('ALTER TABLE member DROP membership_rate_id');
        $this->addSql('DROP INDEX IDX_86FFD285353409CE ON membership');
        $this->addSql('ALTER TABLE membership DROP membership_rate_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE criteria (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, relation VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, value VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE membership_rate (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, price DOUBLE PRECISION NOT NULL, maximum_age INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, reference VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hello_asso_session_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, state TINYINT(1) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_details (id INT AUTO_INCREMENT NOT NULL, my_order_id INT NOT NULL, product VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_845CA2C1BFCDF877 (my_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, rate NUMERIC(10, 3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rate_criteria (rate_id INT NOT NULL, criteria_id INT NOT NULL, INDEX IDX_61C88B20BC999F9F (rate_id), INDEX IDX_61C88B20990BEA15 (criteria_id), PRIMARY KEY(rate_id, criteria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rate_event (rate_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_ABEE1384BC999F9F (rate_id), INDEX IDX_ABEE138471F7E88B (event_id), PRIMARY KEY(rate_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1BFCDF877 FOREIGN KEY (my_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE rate_criteria ADD CONSTRAINT FK_61C88B20990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rate_criteria ADD CONSTRAINT FK_61C88B20BC999F9F FOREIGN KEY (rate_id) REFERENCES rate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rate_event ADD CONSTRAINT FK_ABEE138471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rate_event ADD CONSTRAINT FK_ABEE1384BC999F9F FOREIGN KEY (rate_id) REFERENCES rate (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE event_option');
        $this->addSql('DROP TABLE event_rate');
        $this->addSql('ALTER TABLE event DROP allow_visitors');
        $this->addSql('ALTER TABLE member ADD membership_rate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78353409CE FOREIGN KEY (membership_rate_id) REFERENCES membership_rate (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78353409CE ON member (membership_rate_id)');
        $this->addSql('ALTER TABLE membership ADD membership_rate_id INT NOT NULL');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285353409CE FOREIGN KEY (membership_rate_id) REFERENCES membership_rate (id)');
        $this->addSql('CREATE INDEX IDX_86FFD285353409CE ON membership (membership_rate_id)');
    }
}
