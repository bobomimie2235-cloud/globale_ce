<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224124839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP titre, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE infos_activite infos_activite LONGTEXT DEFAULT NULL, CHANGE offre_commerciale offre_commerciale LONGTEXT DEFAULT NULL, CHANGE horaires horaires LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_categorie DROP intitule');
        $this->addSql('ALTER TABLE produit_categorie DROP intitule');
        $this->addSql('ALTER TABLE utilisateur CHANGE nom nom VARCHAR(50) NOT NULL, CHANGE prenom prenom VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur_adresse CHANGE code_postal code_postal VARCHAR(5) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD titre VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE infos_activite infos_activite VARCHAR(500) DEFAULT NULL, CHANGE offre_commerciale offre_commerciale VARCHAR(500) DEFAULT NULL, CHANGE horaires horaires VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE article_categorie ADD intitule VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit_categorie ADD intitule VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur_adresse CHANGE code_postal code_postal VARCHAR(20) NOT NULL');
    }
}
