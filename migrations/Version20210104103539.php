<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210104103539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresses_livraison (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, code_postal VARCHAR(45) NOT NULL, ville VARCHAR(45) NOT NULL, telephone VARCHAR(45) NOT NULL, email VARCHAR(255) NOT NULL, actif TINYINT(1) NOT NULL, derniere_modification DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, produits_id_id INT DEFAULT NULL, users_id_id INT DEFAULT NULL, note INT NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_8F91ABF0A33EA19 (produits_id_id), INDEX IDX_8F91ABF098333A1E (users_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caracteristiques (id INT AUTO_INCREMENT NOT NULL, produits_id_id INT DEFAULT NULL, types_caracteristiques_id_id INT DEFAULT NULL, valeur VARCHAR(255) NOT NULL, INDEX IDX_61B5DA1DA33EA19 (produits_id_id), INDEX IDX_61B5DA1D514692B1 (types_caracteristiques_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_produits (categories_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_68D376B5A21214B7 (categories_id), INDEX IDX_68D376B5CD11A2CF (produits_id), PRIMARY KEY(categories_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_categories (categories_source INT NOT NULL, categories_target INT NOT NULL, INDEX IDX_9B7D066057E3414B (categories_source), INDEX IDX_9B7D06604E0611C4 (categories_target), PRIMARY KEY(categories_source, categories_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code_promo_users (id INT AUTO_INCREMENT NOT NULL, codes_promo_id_id INT DEFAULT NULL, users_id_id INT DEFAULT NULL, date_utilisation DATETIME NOT NULL, INDEX IDX_B4EB70CF2A3C1054 (codes_promo_id_id), INDEX IDX_B4EB70CF98333A1E (users_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codes_promo (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type_promo VARCHAR(255) NOT NULL, valeur INT NOT NULL, date_debut_validite DATETIME NOT NULL, date_fin_validite DATETIME NOT NULL, minimum_achat INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factures (id INT AUTO_INCREMENT NOT NULL, users_id_id INT DEFAULT NULL, codes_promo_id_id INT DEFAULT NULL, id_adresse_livraison_id INT DEFAULT NULL, date_creation DATETIME NOT NULL, montant_total INT NOT NULL, montant_ht INT NOT NULL, montant_ttc INT NOT NULL, message VARCHAR(255) DEFAULT NULL, INDEX IDX_647590B98333A1E (users_id_id), INDEX IDX_647590B2A3C1054 (codes_promo_id_id), INDEX IDX_647590BB2DFFB91 (id_adresse_livraison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factures_produits (id INT AUTO_INCREMENT NOT NULL, factures_id_id INT DEFAULT NULL, produits_id_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_F244436A8F63384 (factures_id_id), INDEX IDX_F244436A33EA19 (produits_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, produits_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6ACD11A2CF (produits_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paniers (id INT AUTO_INCREMENT NOT NULL, users_id_id INT DEFAULT NULL, codes_promo_id_id INT DEFAULT NULL, id_adresses_livraison_id INT DEFAULT NULL, commande_terminee TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, montant_ht INT NOT NULL, montant_ttc INT NOT NULL, date_modification DATETIME NOT NULL, message VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4899903698333A1E (users_id_id), INDEX IDX_489990362A3C1054 (codes_promo_id_id), UNIQUE INDEX UNIQ_48999036FFAE8CEC (id_adresses_livraison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paniers_produits (id INT AUTO_INCREMENT NOT NULL, paniers_id_id INT DEFAULT NULL, produits_id_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_7B1D2CAB4898C33D (paniers_id_id), INDEX IDX_7B1D2CABA33EA19 (produits_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, taux_tva_id_id INT NOT NULL, ugs VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, est_publie TINYINT(1) NOT NULL, mis_en_avant TINYINT(1) NOT NULL, visibilite_catalogue TINYINT(1) NOT NULL, description_courte VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut_promo DATE DEFAULT NULL, date_fin_promo DATE DEFAULT NULL, etat_tva TINYINT(1) NOT NULL, quantite_stock INT NOT NULL, limite_basse_stock INT NOT NULL, commande_sans_stock TINYINT(1) NOT NULL, vente_individuelle TINYINT(1) NOT NULL, est_evaluable TINYINT(1) NOT NULL, tarif INT NOT NULL, tarif_promo INT DEFAULT NULL, delai_telechargement INT DEFAULT NULL, nombre_telechargements INT DEFAULT NULL, date_creation DATE NOT NULL, INDEX IDX_BE2DDF8CEF054022 (taux_tva_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_produits (produits_source INT NOT NULL, produits_target INT NOT NULL, INDEX IDX_411B275ECE9B6490 (produits_source), INDEX IDX_411B275ED77E341F (produits_target), PRIMARY KEY(produits_source, produits_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taux_tva (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, taux INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE types_caracteristiques (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(45) NOT NULL, ville VARCHAR(45) NOT NULL, telephone VARCHAR(45) NOT NULL, email VARCHAR(45) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', date_creation DATETIME NOT NULL, date_modification DATETIME NOT NULL, pseudo VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, id_stripe VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A33EA19 FOREIGN KEY (produits_id_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF098333A1E FOREIGN KEY (users_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE caracteristiques ADD CONSTRAINT FK_61B5DA1DA33EA19 FOREIGN KEY (produits_id_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE caracteristiques ADD CONSTRAINT FK_61B5DA1D514692B1 FOREIGN KEY (types_caracteristiques_id_id) REFERENCES types_caracteristiques (id)');
        $this->addSql('ALTER TABLE categories_produits ADD CONSTRAINT FK_68D376B5A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_produits ADD CONSTRAINT FK_68D376B5CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_categories ADD CONSTRAINT FK_9B7D066057E3414B FOREIGN KEY (categories_source) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_categories ADD CONSTRAINT FK_9B7D06604E0611C4 FOREIGN KEY (categories_target) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE code_promo_users ADD CONSTRAINT FK_B4EB70CF2A3C1054 FOREIGN KEY (codes_promo_id_id) REFERENCES codes_promo (id)');
        $this->addSql('ALTER TABLE code_promo_users ADD CONSTRAINT FK_B4EB70CF98333A1E FOREIGN KEY (users_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590B98333A1E FOREIGN KEY (users_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590B2A3C1054 FOREIGN KEY (codes_promo_id_id) REFERENCES codes_promo (id)');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590BB2DFFB91 FOREIGN KEY (id_adresse_livraison_id) REFERENCES adresses_livraison (id)');
        $this->addSql('ALTER TABLE factures_produits ADD CONSTRAINT FK_F244436A8F63384 FOREIGN KEY (factures_id_id) REFERENCES factures (id)');
        $this->addSql('ALTER TABLE factures_produits ADD CONSTRAINT FK_F244436A33EA19 FOREIGN KEY (produits_id_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6ACD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE paniers ADD CONSTRAINT FK_4899903698333A1E FOREIGN KEY (users_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE paniers ADD CONSTRAINT FK_489990362A3C1054 FOREIGN KEY (codes_promo_id_id) REFERENCES codes_promo (id)');
        $this->addSql('ALTER TABLE paniers ADD CONSTRAINT FK_48999036FFAE8CEC FOREIGN KEY (id_adresses_livraison_id) REFERENCES adresses_livraison (id)');
        $this->addSql('ALTER TABLE paniers_produits ADD CONSTRAINT FK_7B1D2CAB4898C33D FOREIGN KEY (paniers_id_id) REFERENCES paniers (id)');
        $this->addSql('ALTER TABLE paniers_produits ADD CONSTRAINT FK_7B1D2CABA33EA19 FOREIGN KEY (produits_id_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CEF054022 FOREIGN KEY (taux_tva_id_id) REFERENCES taux_tva (id)');
        $this->addSql('ALTER TABLE produits_produits ADD CONSTRAINT FK_411B275ECE9B6490 FOREIGN KEY (produits_source) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_produits ADD CONSTRAINT FK_411B275ED77E341F FOREIGN KEY (produits_target) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factures DROP FOREIGN KEY FK_647590BB2DFFB91');
        $this->addSql('ALTER TABLE paniers DROP FOREIGN KEY FK_48999036FFAE8CEC');
        $this->addSql('ALTER TABLE categories_produits DROP FOREIGN KEY FK_68D376B5A21214B7');
        $this->addSql('ALTER TABLE categories_categories DROP FOREIGN KEY FK_9B7D066057E3414B');
        $this->addSql('ALTER TABLE categories_categories DROP FOREIGN KEY FK_9B7D06604E0611C4');
        $this->addSql('ALTER TABLE code_promo_users DROP FOREIGN KEY FK_B4EB70CF2A3C1054');
        $this->addSql('ALTER TABLE factures DROP FOREIGN KEY FK_647590B2A3C1054');
        $this->addSql('ALTER TABLE paniers DROP FOREIGN KEY FK_489990362A3C1054');
        $this->addSql('ALTER TABLE factures_produits DROP FOREIGN KEY FK_F244436A8F63384');
        $this->addSql('ALTER TABLE paniers_produits DROP FOREIGN KEY FK_7B1D2CAB4898C33D');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A33EA19');
        $this->addSql('ALTER TABLE caracteristiques DROP FOREIGN KEY FK_61B5DA1DA33EA19');
        $this->addSql('ALTER TABLE categories_produits DROP FOREIGN KEY FK_68D376B5CD11A2CF');
        $this->addSql('ALTER TABLE factures_produits DROP FOREIGN KEY FK_F244436A33EA19');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6ACD11A2CF');
        $this->addSql('ALTER TABLE paniers_produits DROP FOREIGN KEY FK_7B1D2CABA33EA19');
        $this->addSql('ALTER TABLE produits_produits DROP FOREIGN KEY FK_411B275ECE9B6490');
        $this->addSql('ALTER TABLE produits_produits DROP FOREIGN KEY FK_411B275ED77E341F');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CEF054022');
        $this->addSql('ALTER TABLE caracteristiques DROP FOREIGN KEY FK_61B5DA1D514692B1');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF098333A1E');
        $this->addSql('ALTER TABLE code_promo_users DROP FOREIGN KEY FK_B4EB70CF98333A1E');
        $this->addSql('ALTER TABLE factures DROP FOREIGN KEY FK_647590B98333A1E');
        $this->addSql('ALTER TABLE paniers DROP FOREIGN KEY FK_4899903698333A1E');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE adresses_livraison');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE caracteristiques');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_produits');
        $this->addSql('DROP TABLE categories_categories');
        $this->addSql('DROP TABLE code_promo_users');
        $this->addSql('DROP TABLE codes_promo');
        $this->addSql('DROP TABLE factures');
        $this->addSql('DROP TABLE factures_produits');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE paniers');
        $this->addSql('DROP TABLE paniers_produits');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE produits_produits');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE taux_tva');
        $this->addSql('DROP TABLE types_caracteristiques');
        $this->addSql('DROP TABLE users');
    }
}
