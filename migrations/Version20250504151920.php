<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250504151920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE goal (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date_butoir DATE NOT NULL, pourquoi VARCHAR(255) NOT NULL, INDEX IDX_FCDCEB2EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE task ADD goal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25667D1AFE ON task (goal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25667D1AFE');
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2EA76ED395');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP INDEX IDX_527EDB25667D1AFE ON task');
        $this->addSql('ALTER TABLE task DROP goal_id');
    }
}
