<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922105636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument DROP rating');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6CF11D9C');
        $this->addSql('DROP INDEX IDX_794381C6CF11D9C ON review');
        $this->addSql('ALTER TABLE review CHANGE instrument_id user_noted_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6D1157E70 FOREIGN KEY (user_noted_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_794381C6D1157E70 ON review (user_noted_id)');
        $this->addSql('ALTER TABLE user ADD rating DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument ADD rating DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP rating');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6D1157E70');
        $this->addSql('DROP INDEX IDX_794381C6D1157E70 ON review');
        $this->addSql('ALTER TABLE review CHANGE user_noted_id instrument_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_794381C6CF11D9C ON review (instrument_id)');
    }
}
