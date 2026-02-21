<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260221165534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD utilisateur_id INT DEFAULT NULL, ADD article_categorie_id INT DEFAULT NULL, ADD coupon_reduction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E666FB990BC FOREIGN KEY (article_categorie_id) REFERENCES article_categorie (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6688F9E04A FOREIGN KEY (coupon_reduction_id) REFERENCES coupon_reduction (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66FB88E14F ON article (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_23A0E666FB990BC ON article (article_categorie_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E6688F9E04A ON article (coupon_reduction_id)');
        $this->addSql('ALTER TABLE commande ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DFB88E14F ON commande (utilisateur_id)');
        $this->addSql('ALTER TABLE commande_produit ADD commande_id INT DEFAULT NULL, ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E8782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_DF1E9E8782EA2E54 ON commande_produit (commande_id)');
        $this->addSql('CREATE INDEX IDX_DF1E9E87F347EFB ON commande_produit (produit_id)');
        $this->addSql('ALTER TABLE coupon_reduction ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE coupon_reduction ADD CONSTRAINT FK_792986B67294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_792986B67294869C ON coupon_reduction (article_id)');
        $this->addSql('ALTER TABLE produit ADD produit_categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27565EB850 FOREIGN KEY (produit_categorie_id) REFERENCES produit_categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27565EB850 ON produit (produit_categorie_id)');
        $this->addSql('ALTER TABLE utilisateur ADD utilisateur_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B31AEBC0A0 FOREIGN KEY (utilisateur_groupe_id) REFERENCES utilisateur_groupe (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B31AEBC0A0 ON utilisateur (utilisateur_groupe_id)');
        $this->addSql('ALTER TABLE utilisateur_adresse ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur_adresse ADD CONSTRAINT FK_B954FF84FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_B954FF84FB88E14F ON utilisateur_adresse (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66FB88E14F');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E666FB990BC');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6688F9E04A');
        $this->addSql('DROP INDEX IDX_23A0E66FB88E14F ON article');
        $this->addSql('DROP INDEX IDX_23A0E666FB990BC ON article');
        $this->addSql('DROP INDEX UNIQ_23A0E6688F9E04A ON article');
        $this->addSql('ALTER TABLE article DROP utilisateur_id, DROP article_categorie_id, DROP coupon_reduction_id');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DFB88E14F');
        $this->addSql('DROP INDEX IDX_6EEAA67DFB88E14F ON commande');
        $this->addSql('ALTER TABLE commande DROP utilisateur_id');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E8782EA2E54');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E87F347EFB');
        $this->addSql('DROP INDEX IDX_DF1E9E8782EA2E54 ON commande_produit');
        $this->addSql('DROP INDEX IDX_DF1E9E87F347EFB ON commande_produit');
        $this->addSql('ALTER TABLE commande_produit DROP commande_id, DROP produit_id');
        $this->addSql('ALTER TABLE coupon_reduction DROP FOREIGN KEY FK_792986B67294869C');
        $this->addSql('DROP INDEX UNIQ_792986B67294869C ON coupon_reduction');
        $this->addSql('ALTER TABLE coupon_reduction DROP article_id');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27565EB850');
        $this->addSql('DROP INDEX IDX_29A5EC27565EB850 ON produit');
        $this->addSql('ALTER TABLE produit DROP produit_categorie_id');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B31AEBC0A0');
        $this->addSql('DROP INDEX IDX_1D1C63B31AEBC0A0 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP utilisateur_groupe_id');
        $this->addSql('ALTER TABLE utilisateur_adresse DROP FOREIGN KEY FK_B954FF84FB88E14F');
        $this->addSql('DROP INDEX IDX_B954FF84FB88E14F ON utilisateur_adresse');
        $this->addSql('ALTER TABLE utilisateur_adresse DROP utilisateur_id');
    }
}
