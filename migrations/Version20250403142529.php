<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403142529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE filtre_valeur (id INT AUTO_INCREMENT NOT NULL, filtre_id INT NOT NULL, valeur VARCHAR(255) NOT NULL, INDEX IDX_88B084A5CC9B96EA (filtre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filtre_valeur ADD CONSTRAINT FK_88B084A5CC9B96EA FOREIGN KEY (filtre_id) REFERENCES filtre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE filtre_valeur DROP FOREIGN KEY FK_88B084A5CC9B96EA');
        $this->addSql('DROP TABLE filtre_valeur');
    }
}
