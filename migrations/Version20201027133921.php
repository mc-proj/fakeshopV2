<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201027133921 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, produits_id_id INT DEFAULT NULL, users_id_id INT DEFAULT NULL, note INT NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_8F91ABF0A33EA19 (produits_id_id), INDEX IDX_8F91ABF098333A1E (users_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caracteristiques (id INT AUTO_INCREMENT NOT NULL, produits_id_id INT DEFAULT NULL, types_caracteristiques_id_id INT DEFAULT NULL, valeur DOUBLE PRECISION NOT NULL, INDEX IDX_61B5DA1DA33EA19 (produits_id_id), INDEX IDX_61B5DA1D514692B1 (types_caracteristiques_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_produits (categories_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_68D376B5A21214B7 (categories_id), INDEX IDX_68D376B5CD11A2CF (produits_id), PRIMARY KEY(categories_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code_promo (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code_promo_users (id INT AUTO_INCREMENT NOT NULL, codes_promo_id_id INT DEFAULT NULL, users_id_id INT DEFAULT NULL, date_utilisation DATETIME NOT NULL, INDEX IDX_B4EB70CF2A3C1054 (codes_promo_id_id), INDEX IDX_B4EB70CF98333A1E (users_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codes_promo (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type_promo VARCHAR(255) NOT NULL, valeur DOUBLE PRECISION NOT NULL, date_debut_validite DATETIME NOT NULL, date_fin_validite DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factures (id INT AUTO_INCREMENT NOT NULL, users_id_id INT DEFAULT NULL, codes_promo_id_id INT DEFAULT NULL, date_creation DATETIME NOT NULL, montant_total DOUBLE PRECISION NOT NULL, INDEX IDX_647590B98333A1E (users_id_id), INDEX IDX_647590B2A3C1054 (codes_promo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factures_produits (id INT AUTO_INCREMENT NOT NULL, factures_id_id INT DEFAULT NULL, produits_id_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_F244436A8F63384 (factures_id_id), INDEX IDX_F244436A33EA19 (produits_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paniers (id INT AUTO_INCREMENT NOT NULL, users_id_id INT DEFAULT NULL, codes_promo_id_id INT DEFAULT NULL, commande_terminee TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, montant DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_4899903698333A1E (users_id_id), INDEX IDX_489990362A3C1054 (codes_promo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paniers_produits (id INT AUTO_INCREMENT NOT NULL, paniers_id_id INT DEFAULT NULL, produits_id_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_7B1D2CAB4898C33D (paniers_id_id), INDEX IDX_7B1D2CABA33EA19 (produits_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, taux_tva_id_id INT DEFAULT NULL, ugs VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, est_publie TINYINT(1) NOT NULL, mis_en_avant TINYINT(1) NOT NULL, visibilite_catalogue TINYINT(1) NOT NULL, description_courte VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut_promo DATE DEFAULT NULL, date_fin_promo DATE DEFAULT NULL, etat_tva TINYINT(1) NOT NULL, quantite_stock INT NOT NULL, limite_basse_stock INT NOT NULL, commande_sans_stock TINYINT(1) NOT NULL, vente_individuelle TINYINT(1) NOT NULL, est_evaluable TINYINT(1) NOT NULL, tarif DOUBLE PRECISION NOT NULL, tarif_promo DOUBLE PRECISION DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, delai_telechargement INT DEFAULT NULL, nombre_telechargements INT DEFAULT NULL, INDEX IDX_BE2DDF8CEF054022 (taux_tva_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_categories (id INT AUTO_INCREMENT NOT NULL, categories_id_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_DC8B13827B478B1A (categories_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taux_tva (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, taux DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE types_caracteristiques (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(45) NOT NULL, ville VARCHAR(45) NOT NULL, telephone VARCHAR(45) NOT NULL, email VARCHAR(45) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', date_creation DATETIME NOT NULL, date_modification DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A33EA19 FOREIGN KEY (produits_id_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF098333A1E FOREIGN KEY (users_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE caracteristiques ADD CONSTRAINT FK_61B5DA1DA33EA19 FOREIGN KEY (produits_id_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE caracteristiques ADD CONSTRAINT FK_61B5DA1D514692B1 FOREIGN KEY (types_caracteristiques_id_id) REFERENCES types_caracteristiques (id)');
        $this->addSql('ALTER TABLE categories_produits ADD CONSTRAINT FK_68D376B5A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_produits ADD CONSTRAINT FK_68D376B5CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE code_promo_users ADD CONSTRAINT FK_B4EB70CF2A3C1054 FOREIGN KEY (codes_promo_id_id) REFERENCES codes_promo (id)');
        $this->addSql('ALTER TABLE code_promo_users ADD CONSTRAINT FK_B4EB70CF98333A1E FOREIGN KEY (users_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590B98333A1E FOREIGN KEY (users_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590B2A3C1054 FOREIGN KEY (codes_promo_id_id) REFERENCES codes_promo (id)');
        $this->addSql('ALTER TABLE factures_produits ADD CONSTRAINT FK_F244436A8F63384 FOREIGN KEY (factures_id_id) REFERENCES factures (id)');
        $this->addSql('ALTER TABLE factures_produits ADD CONSTRAINT FK_F244436A33EA19 FOREIGN KEY (produits_id_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE paniers ADD CONSTRAINT FK_4899903698333A1E FOREIGN KEY (users_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE paniers ADD CONSTRAINT FK_489990362A3C1054 FOREIGN KEY (codes_promo_id_id) REFERENCES codes_promo (id)');
        $this->addSql('ALTER TABLE paniers_produits ADD CONSTRAINT FK_7B1D2CAB4898C33D FOREIGN KEY (paniers_id_id) REFERENCES paniers (id)');
        $this->addSql('ALTER TABLE paniers_produits ADD CONSTRAINT FK_7B1D2CABA33EA19 FOREIGN KEY (produits_id_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CEF054022 FOREIGN KEY (taux_tva_id_id) REFERENCES taux_tva (id)');
        $this->addSql('ALTER TABLE sous_categories ADD CONSTRAINT FK_DC8B13827B478B1A FOREIGN KEY (categories_id_id) REFERENCES categories (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_produits DROP FOREIGN KEY FK_68D376B5A21214B7');
        $this->addSql('ALTER TABLE sous_categories DROP FOREIGN KEY FK_DC8B13827B478B1A');
        $this->addSql('ALTER TABLE code_promo_users DROP FOREIGN KEY FK_B4EB70CF2A3C1054');
        $this->addSql('ALTER TABLE factures DROP FOREIGN KEY FK_647590B2A3C1054');
        $this->addSql('ALTER TABLE paniers DROP FOREIGN KEY FK_489990362A3C1054');
        $this->addSql('ALTER TABLE factures_produits DROP FOREIGN KEY FK_F244436A8F63384');
        $this->addSql('ALTER TABLE paniers_produits DROP FOREIGN KEY FK_7B1D2CAB4898C33D');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A33EA19');
        $this->addSql('ALTER TABLE caracteristiques DROP FOREIGN KEY FK_61B5DA1DA33EA19');
        $this->addSql('ALTER TABLE categories_produits DROP FOREIGN KEY FK_68D376B5CD11A2CF');
        $this->addSql('ALTER TABLE factures_produits DROP FOREIGN KEY FK_F244436A33EA19');
        $this->addSql('ALTER TABLE paniers_produits DROP FOREIGN KEY FK_7B1D2CABA33EA19');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CEF054022');
        $this->addSql('ALTER TABLE caracteristiques DROP FOREIGN KEY FK_61B5DA1D514692B1');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF098333A1E');
        $this->addSql('ALTER TABLE code_promo_users DROP FOREIGN KEY FK_B4EB70CF98333A1E');
        $this->addSql('ALTER TABLE factures DROP FOREIGN KEY FK_647590B98333A1E');
        $this->addSql('ALTER TABLE paniers DROP FOREIGN KEY FK_4899903698333A1E');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE caracteristiques');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_produits');
        $this->addSql('DROP TABLE code_promo');
        $this->addSql('DROP TABLE code_promo_users');
        $this->addSql('DROP TABLE codes_promo');
        $this->addSql('DROP TABLE factures');
        $this->addSql('DROP TABLE factures_produits');
        $this->addSql('DROP TABLE paniers');
        $this->addSql('DROP TABLE paniers_produits');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE sous_categories');
        $this->addSql('DROP TABLE taux_tva');
        $this->addSql('DROP TABLE types_caracteristiques');
        $this->addSql('DROP TABLE users');
    }
}
