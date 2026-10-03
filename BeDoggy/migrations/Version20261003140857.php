<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261003140857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chien ADD CONSTRAINT FK_13A4067EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prestation ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chien DROP FOREIGN KEY FK_13A4067EFB88E14F');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5FB88E14F');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5F6203804');
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C80E95E18');
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C9E45C554');
        $this->addSql('ALTER TABLE prestation DROP image');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D60322AC');
    }
}
