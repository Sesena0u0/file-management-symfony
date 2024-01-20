<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240120112959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, folder_id INT NOT NULL, size DOUBLE PRECISION NOT NULL, link MEDIUMTEXT NOT NULL, ext VARCHAR(255) NOT NULL, INDEX IDX_8C9F3610162CB942 (folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE folder ADD folder_child_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CD14D761F1 FOREIGN KEY (folder_child_id) REFERENCES folder (id)');
        $this->addSql('CREATE INDEX IDX_ECA209CD14D761F1 ON folder (folder_child_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610162CB942');
        $this->addSql('DROP TABLE file');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD14D761F1');
        $this->addSql('DROP INDEX IDX_ECA209CD14D761F1 ON folder');
        $this->addSql('ALTER TABLE folder DROP folder_child_id');
    }
}
