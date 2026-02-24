<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224130830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_6514B6AA19D83D25 ON utilisateur_groupe');
        $this->addSql('ALTER TABLE utilisateur_groupe DROP reference_groupe, CHANGE nom_groupe nom_groupe VARCHAR(60) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur_groupe ADD reference_groupe VARCHAR(255) NOT NULL, CHANGE nom_groupe nom_groupe VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6514B6AA19D83D25 ON utilisateur_groupe (reference_groupe)');
    }
}
