<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110161127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD last_name VARCHAR(50) NOT NULL, ADD first_name VARCHAR(50) NOT NULL, ADD adhesion_state VARCHAR(50) NOT NULL, ADD medical_certificate VARCHAR(50) NOT NULL, DROP lastName, DROP firstName, DROP adhesionState, DROP medicalCertificate, CHANGE postalcode postal_code INT NOT NULL, CHANGE emergencyphone emergency_phone VARCHAR(15) NOT NULL, CHANGE dateadhesion date_adhesion DATE NOT NULL');
        $this->addSql('ALTER TABLE user ADD last_name VARCHAR(55) NOT NULL, ADD first_name VARCHAR(55) NOT NULL, DROP lastName, DROP firstName');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD lastName VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD firstName VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD adhesionState VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD medicalCertificate VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP last_name, DROP first_name, DROP adhesion_state, DROP medical_certificate, CHANGE postal_code postalCode INT NOT NULL, CHANGE emergency_phone emergencyPhone VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE date_adhesion dateAdhesion DATE NOT NULL');
        $this->addSql('ALTER TABLE user ADD lastName VARCHAR(55) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD firstName VARCHAR(55) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP last_name, DROP first_name');
    }
}
