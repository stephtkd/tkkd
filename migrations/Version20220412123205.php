<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220412123205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_picture DROP FOREIGN KEY FK_430C129BE645AE9A');
        $this->addSql('DROP INDEX IDX_430C129BE645AE9A ON album_picture');
        $this->addSql('ALTER TABLE album_picture CHANGE album_picture_id tag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE album_picture ADD CONSTRAINT FK_430C129BBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('CREATE INDEX IDX_430C129BBAD26311 ON album_picture (tag_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_picture DROP FOREIGN KEY FK_430C129BBAD26311');
        $this->addSql('DROP INDEX IDX_430C129BBAD26311 ON album_picture');
        $this->addSql('ALTER TABLE album_picture CHANGE tag_id album_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE album_picture ADD CONSTRAINT FK_430C129BE645AE9A FOREIGN KEY (album_picture_id) REFERENCES tag (id)');
        $this->addSql('CREATE INDEX IDX_430C129BE645AE9A ON album_picture (album_picture_id)');
    }
}
