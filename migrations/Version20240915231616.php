<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240915231616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument CHANGE seller seller_id INT NOT NULL');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DD8DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3CBF69DD8DE820D9 ON instrument (seller_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DD8DE820D9');
        $this->addSql('DROP INDEX IDX_3CBF69DD8DE820D9 ON instrument');
        $this->addSql('ALTER TABLE instrument CHANGE seller_id seller INT NOT NULL');
    }
}
