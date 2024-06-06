<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605135403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE crawling (id INT AUTO_INCREMENT NOT NULL, server_instance_id INT NOT NULL, target VARCHAR(255) NOT NULL, INDEX IDX_D6B553D572318BC6 (server_instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE crawling ADD CONSTRAINT FK_D6B553D572318BC6 FOREIGN KEY (server_instance_id) REFERENCES server_instance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE crawling DROP FOREIGN KEY FK_D6B553D572318BC6');
        $this->addSql('DROP TABLE crawling');
    }
}
