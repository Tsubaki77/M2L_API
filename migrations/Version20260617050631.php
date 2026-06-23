<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260617050631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Les gestionnaires déjà existants doivent recevoir un statut par défaut,
        // sinon la colonne NOT NULL ferait échouer la migration
        $this->addSql("ALTER TABLE gestionnaires ADD telephone VARCHAR(20) DEFAULT NULL, ADD statut VARCHAR(20) NOT NULL DEFAULT 'actif'");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gestionnaires DROP telephone, DROP statut');
    }
}
