<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220412122233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_picture ADD album_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE album_picture ADD CONSTRAINT FK_430C129BE645AE9A FOREIGN KEY (album_picture_id) REFERENCES tag (id)');
        $this->addSql('CREATE INDEX IDX_430C129BE645AE9A ON album_picture (album_picture_id)');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783BAD26311');
        $this->addSql('DROP INDEX IDX_389B783BAD26311 ON tag');
        $this->addSql('ALTER TABLE tag DROP tag_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_picture DROP FOREIGN KEY FK_430C129BE645AE9A');
        $this->addSql('DROP INDEX IDX_430C129BE645AE9A ON album_picture');
        $this->addSql('ALTER TABLE album_picture DROP album_picture_id');
        $this->addSql('ALTER TABLE tag ADD tag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783BAD26311 FOREIGN KEY (tag_id) REFERENCES album_picture (id)');
        $this->addSql('CREATE INDEX IDX_389B783BAD26311 ON tag (tag_id)');
    }
}
