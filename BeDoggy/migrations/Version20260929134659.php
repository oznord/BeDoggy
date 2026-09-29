<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260929134659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chien (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, age INT NOT NULL, genre VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, reponse VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE demande_prestation (demande_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_A704850C80E95E18 (demande_id), INDEX IDX_A704850C9E45C554 (prestation_id), PRIMARY KEY (demande_id, prestation_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, nb_seance INT NOT NULL, prix_seance DOUBLE PRECISION NOT NULL, temps_seance INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, avoir_id INT DEFAULT NULL, INDEX IDX_57698A6AC36D46DB (avoir_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, statut_demande_id INT DEFAULT NULL, INDEX IDX_E564F0BFF5225311 (statut_demande_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, elever_id INT DEFAULT NULL, faire_id INT DEFAULT NULL, INDEX IDX_1D1C63B37AA86427 (elever_id), INDEX IDX_1D1C63B36776C72A (faire_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6AC36D46DB FOREIGN KEY (avoir_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE statut ADD CONSTRAINT FK_E564F0BFF5225311 FOREIGN KEY (statut_demande_id) REFERENCES demande (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B37AA86427 FOREIGN KEY (elever_id) REFERENCES chien (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B36776C72A FOREIGN KEY (faire_id) REFERENCES demande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C80E95E18');
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C9E45C554');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6AC36D46DB');
        $this->addSql('ALTER TABLE statut DROP FOREIGN KEY FK_E564F0BFF5225311');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B37AA86427');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B36776C72A');
        $this->addSql('DROP TABLE chien');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE demande_prestation');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE utilisateur');
    }
}
