<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414103217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hideout (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_hideout (mission_id INT NOT NULL, hideout_id INT NOT NULL, INDEX IDX_BD137514BE6CAE90 (mission_id), INDEX IDX_BD137514E9982FD7 (hideout_id), PRIMARY KEY(mission_id, hideout_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mission_hideout ADD CONSTRAINT FK_BD137514BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_hideout ADD CONSTRAINT FK_BD137514E9982FD7 FOREIGN KEY (hideout_id) REFERENCES hideout (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission_hideout DROP FOREIGN KEY FK_BD137514BE6CAE90');
        $this->addSql('ALTER TABLE mission_hideout DROP FOREIGN KEY FK_BD137514E9982FD7');
        $this->addSql('DROP TABLE hideout');
        $this->addSql('DROP TABLE mission_hideout');
    }
}
