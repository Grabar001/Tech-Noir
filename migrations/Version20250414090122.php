<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414090122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_filtre_valeur DROP FOREIGN KEY FK_A6EE227B3256915B');
        $this->addSql('DROP INDEX IDX_A6EE227B3256915B ON produit_filtre_valeur');
        $this->addSql('ALTER TABLE produit_filtre_valeur CHANGE relation_id filtre_valeur_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit_filtre_valeur ADD CONSTRAINT FK_A6EE227B2D7CB4F0 FOREIGN KEY (filtre_valeur_id) REFERENCES filtre_valeur (id)');
        $this->addSql('CREATE INDEX IDX_A6EE227B2D7CB4F0 ON produit_filtre_valeur (filtre_valeur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_filtre_valeur DROP FOREIGN KEY FK_A6EE227B2D7CB4F0');
        $this->addSql('DROP INDEX IDX_A6EE227B2D7CB4F0 ON produit_filtre_valeur');
        $this->addSql('ALTER TABLE produit_filtre_valeur CHANGE filtre_valeur_id relation_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit_filtre_valeur ADD CONSTRAINT FK_A6EE227B3256915B FOREIGN KEY (relation_id) REFERENCES filtre_valeur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A6EE227B3256915B ON produit_filtre_valeur (relation_id)');
    }
}
