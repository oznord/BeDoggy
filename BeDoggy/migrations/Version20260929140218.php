<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260929140218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850C9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX IDX_57698A6AC36D46DB ON role');
        $this->addSql('ALTER TABLE role DROP avoir_id');
        $this->addSql('DROP INDEX IDX_E564F0BFF5225311 ON statut');
        $this->addSql('ALTER TABLE statut DROP statut_demande_id');
        $this->addSql('DROP INDEX IDX_1D1C63B36776C72A ON utilisateur');
        $this->addSql('DROP INDEX IDX_1D1C63B37AA86427 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP elever_id, DROP faire_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C80E95E18');
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850C9E45C554');
        $this->addSql('ALTER TABLE role ADD avoir_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_57698A6AC36D46DB ON role (avoir_id)');
        $this->addSql('ALTER TABLE statut ADD statut_demande_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_E564F0BFF5225311 ON statut (statut_demande_id)');
        $this->addSql('ALTER TABLE utilisateur ADD elever_id INT DEFAULT NULL, ADD faire_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_1D1C63B36776C72A ON utilisateur (faire_id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B37AA86427 ON utilisateur (elever_id)');
    }
}
