<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250504152703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE goal_category (goal_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_B2368CF4667D1AFE (goal_id), INDEX IDX_B2368CF412469DE2 (category_id), PRIMARY KEY(goal_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE goal_category ADD CONSTRAINT FK_B2368CF4667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE goal_category ADD CONSTRAINT FK_B2368CF412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goal_category DROP FOREIGN KEY FK_B2368CF4667D1AFE');
        $this->addSql('ALTER TABLE goal_category DROP FOREIGN KEY FK_B2368CF412469DE2');
        $this->addSql('DROP TABLE goal_category');
    }
}
