<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520095908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_subscription (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, member_id INT NOT NULL, user_id INT NOT NULL, event_rate_id INT NOT NULL, payment_id INT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_4ED56E2071F7E88B (event_id), INDEX IDX_4ED56E207597D3FE (member_id), INDEX IDX_4ED56E20A76ED395 (user_id), INDEX IDX_4ED56E2039CE3630 (event_rate_id), INDEX IDX_4ED56E204C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_subscription_event_option (event_subscription_id INT NOT NULL, event_option_id INT NOT NULL, INDEX IDX_BE22924253CBE3EC (event_subscription_id), INDEX IDX_BE22924221512352 (event_option_id), PRIMARY KEY(event_subscription_id, event_option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, mean VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, reference VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, details JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_subscription ADD CONSTRAINT FK_4ED56E2071F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_subscription ADD CONSTRAINT FK_4ED56E207597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE event_subscription ADD CONSTRAINT FK_4ED56E20A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE event_subscription ADD CONSTRAINT FK_4ED56E2039CE3630 FOREIGN KEY (event_rate_id) REFERENCES event_rate (id)');
        $this->addSql('ALTER TABLE event_subscription ADD CONSTRAINT FK_4ED56E204C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE event_subscription_event_option ADD CONSTRAINT FK_BE22924253CBE3EC FOREIGN KEY (event_subscription_id) REFERENCES event_subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_subscription_event_option ADD CONSTRAINT FK_BE22924221512352 FOREIGN KEY (event_option_id) REFERENCES event_option (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE membership');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_subscription_event_option DROP FOREIGN KEY FK_BE22924253CBE3EC');
        $this->addSql('ALTER TABLE event_subscription DROP FOREIGN KEY FK_4ED56E204C3A3BB');
        $this->addSql('CREATE TABLE membership (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, season_year INT DEFAULT NULL, suscription_date DATETIME DEFAULT NULL, membership_up_to_date TINYINT(1) DEFAULT NULL, membership_state VARCHAR(55) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_86FFD2857597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD2857597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('DROP TABLE event_subscription');
        $this->addSql('DROP TABLE event_subscription_event_option');
        $this->addSql('DROP TABLE payment');
    }
}
