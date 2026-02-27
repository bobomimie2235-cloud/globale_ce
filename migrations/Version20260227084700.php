<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227084700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coupon_categorie (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE coupon_reduction ADD adresse VARCHAR(255) NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD offre_commerciale VARCHAR(255) NOT NULL, ADD logo VARCHAR(255) NOT NULL, ADD coupon_categorie_id INT DEFAULT NULL, DROP description');
        $this->addSql('ALTER TABLE coupon_reduction ADD CONSTRAINT FK_792986B6F430B7F5 FOREIGN KEY (coupon_categorie_id) REFERENCES coupon_categorie (id)');
        $this->addSql('CREATE INDEX IDX_792986B6F430B7F5 ON coupon_reduction (coupon_categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE coupon_categorie');
        $this->addSql('ALTER TABLE coupon_reduction DROP FOREIGN KEY FK_792986B6F430B7F5');
        $this->addSql('DROP INDEX IDX_792986B6F430B7F5 ON coupon_reduction');
        $this->addSql('ALTER TABLE coupon_reduction ADD description VARCHAR(500) DEFAULT NULL, DROP adresse, DROP ville, DROP offre_commerciale, DROP logo, DROP coupon_categorie_id');
    }
}
