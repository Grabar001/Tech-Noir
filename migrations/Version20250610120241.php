<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610120241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_497DD634989D9B62 ON categorie (slug)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commande (id SERIAL NOT NULL, user_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, total DOUBLE PRECISION NOT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN commande.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commande_produit (id SERIAL NOT NULL, produit_id INT NOT NULL, commande_id INT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DF1E9E87F347EFB ON commande_produit (produit_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DF1E9E8782EA2E54 ON commande_produit (commande_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE filtre (id SERIAL NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, champ VARCHAR(50) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F9945E4CBCF5E72D ON filtre (categorie_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE filtre_valeur (id SERIAL NOT NULL, filtre_id INT NOT NULL, valeur VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_88B084A5CC9B96EA ON filtre_valeur (filtre_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produit (id SERIAL NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description TEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, reduction INT DEFAULT NULL, en_stock BOOLEAN NOT NULL, nouveau BOOLEAN NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produit_filtre_valeur (produit_id INT NOT NULL, filtre_valeur_id INT NOT NULL, PRIMARY KEY(produit_id, filtre_valeur_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A6EE227BF347EFB ON produit_filtre_valeur (produit_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A6EE227B2D7CB4F0 ON produit_filtre_valeur (filtre_valeur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E8782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filtre ADD CONSTRAINT FK_F9945E4CBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filtre_valeur ADD CONSTRAINT FK_88B084A5CC9B96EA FOREIGN KEY (filtre_id) REFERENCES filtre (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit_filtre_valeur ADD CONSTRAINT FK_A6EE227BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit_filtre_valeur ADD CONSTRAINT FK_A6EE227B2D7CB4F0 FOREIGN KEY (filtre_valeur_id) REFERENCES filtre_valeur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP CONSTRAINT FK_6EEAA67DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit DROP CONSTRAINT FK_DF1E9E87F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande_produit DROP CONSTRAINT FK_DF1E9E8782EA2E54
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filtre DROP CONSTRAINT FK_F9945E4CBCF5E72D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE filtre_valeur DROP CONSTRAINT FK_88B084A5CC9B96EA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27BCF5E72D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit_filtre_valeur DROP CONSTRAINT FK_A6EE227BF347EFB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit_filtre_valeur DROP CONSTRAINT FK_A6EE227B2D7CB4F0
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commande
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commande_produit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE filtre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE filtre_valeur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produit_filtre_valeur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
