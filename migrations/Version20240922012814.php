<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922012814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review ADD instrument_id INT NOT NULL, DROP reviewer_id, CHANGE reviewed_user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('CREATE INDEX IDX_794381C6CF11D9C ON review (instrument_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6CF11D9C');
        $this->addSql('DROP INDEX IDX_794381C6A76ED395 ON review');
        $this->addSql('DROP INDEX IDX_794381C6CF11D9C ON review');
        $this->addSql('ALTER TABLE review ADD reviewer_id INT DEFAULT NULL, ADD reviewed_user_id INT NOT NULL, DROP user_id, DROP instrument_id');
    }
}
