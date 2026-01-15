<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260114214130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan ADD service_id INT NOT NULL');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7DED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_DD5A5B7DED5CA9E6 ON plan (service_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan DROP CONSTRAINT FK_DD5A5B7DED5CA9E6');
        $this->addSql('DROP INDEX IDX_DD5A5B7DED5CA9E6');
        $this->addSql('ALTER TABLE plan DROP service_id');
    }
}
