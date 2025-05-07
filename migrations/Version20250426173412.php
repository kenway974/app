<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426173412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stat_category (stat_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_8F7BC8569502F0B (stat_id), INDEX IDX_8F7BC85612469DE2 (category_id), PRIMARY KEY(stat_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_category (task_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_468CF38D8DB60186 (task_id), INDEX IDX_468CF38D12469DE2 (category_id), PRIMARY KEY(task_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stat_category ADD CONSTRAINT FK_8F7BC8569502F0B FOREIGN KEY (stat_id) REFERENCES stat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stat_category ADD CONSTRAINT FK_8F7BC85612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_category ADD CONSTRAINT FK_468CF38D8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_category ADD CONSTRAINT FK_468CF38D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE stat ADD category JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stat_category DROP FOREIGN KEY FK_8F7BC8569502F0B');
        $this->addSql('ALTER TABLE stat_category DROP FOREIGN KEY FK_8F7BC85612469DE2');
        $this->addSql('ALTER TABLE task_category DROP FOREIGN KEY FK_468CF38D8DB60186');
        $this->addSql('ALTER TABLE task_category DROP FOREIGN KEY FK_468CF38D12469DE2');
        $this->addSql('DROP TABLE stat_category');
        $this->addSql('DROP TABLE task_category');
        $this->addSql('ALTER TABLE category CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE stat DROP category');
    }
}
