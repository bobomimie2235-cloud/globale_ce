<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306131817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coupon_reduction ADD departement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE coupon_reduction ADD CONSTRAINT FK_792986B6CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('CREATE INDEX IDX_792986B6CCF9E01E ON coupon_reduction (departement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coupon_reduction DROP FOREIGN KEY FK_792986B6CCF9E01E');
        $this->addSql('DROP INDEX IDX_792986B6CCF9E01E ON coupon_reduction');
        $this->addSql('ALTER TABLE coupon_reduction DROP departement_id');
    }
}
