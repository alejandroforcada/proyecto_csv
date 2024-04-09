<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409120018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ordinadors (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, entitat VARCHAR(255) DEFAULT NULL, codi_inventari VARCHAR(255) DEFAULT NULL, estat VARCHAR(255) DEFAULT NULL, tipus VARCHAR(255) DEFAULT NULL, codi_article VARCHAR(255) DEFAULT NULL, descripcio_codi_article VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, fabricant VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, sistema_operatiu_nom VARCHAR(255) DEFAULT NULL, sistema_operatiu_versio VARCHAR(255) DEFAULT NULL, espai_desti VARCHAR(255) DEFAULT NULL, descripcio_espai_desti VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ordinadors');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
