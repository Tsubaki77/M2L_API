<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260623075917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD ligue_id INT NOT NULL, DROP ligue');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F0604D7328E5 FOREIGN KEY (ligue_id) REFERENCES ligue (id)');
        $this->addSql('CREATE INDEX IDX_90D3F0604D7328E5 ON adherent (ligue_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F0604D7328E5');
        $this->addSql('DROP INDEX IDX_90D3F0604D7328E5 ON adherent');
        $this->addSql('ALTER TABLE adherent ADD ligue VARCHAR(255) NOT NULL, DROP ligue_id');
    }
}
