<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414081208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_filtre_valeur (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, relation_id INT NOT NULL, INDEX IDX_A6EE227BF347EFB (produit_id), INDEX IDX_A6EE227B3256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_filtre_valeur ADD CONSTRAINT FK_A6EE227BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit_filtre_valeur ADD CONSTRAINT FK_A6EE227B3256915B FOREIGN KEY (relation_id) REFERENCES filtre_valeur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_filtre_valeur DROP FOREIGN KEY FK_A6EE227BF347EFB');
        $this->addSql('ALTER TABLE produit_filtre_valeur DROP FOREIGN KEY FK_A6EE227B3256915B');
        $this->addSql('DROP TABLE produit_filtre_valeur');
    }
}
