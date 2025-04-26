<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421195852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stat (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(25) NOT NULL, score INT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stat_user (stat_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_ACDB2D1D9502F0B (stat_id), INDEX IDX_ACDB2D1DA76ED395 (user_id), PRIMARY KEY(stat_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stat_task (stat_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_733620719502F0B (stat_id), INDEX IDX_733620718DB60186 (task_id), PRIMARY KEY(stat_id, task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stat_user ADD CONSTRAINT FK_ACDB2D1D9502F0B FOREIGN KEY (stat_id) REFERENCES stat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stat_user ADD CONSTRAINT FK_ACDB2D1DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stat_task ADD CONSTRAINT FK_733620719502F0B FOREIGN KEY (stat_id) REFERENCES stat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stat_task ADD CONSTRAINT FK_733620718DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task CHANGE sous_taches sous_taches VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP stats');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stat_user DROP FOREIGN KEY FK_ACDB2D1D9502F0B');
        $this->addSql('ALTER TABLE stat_user DROP FOREIGN KEY FK_ACDB2D1DA76ED395');
        $this->addSql('ALTER TABLE stat_task DROP FOREIGN KEY FK_733620719502F0B');
        $this->addSql('ALTER TABLE stat_task DROP FOREIGN KEY FK_733620718DB60186');
        $this->addSql('DROP TABLE stat');
        $this->addSql('DROP TABLE stat_user');
        $this->addSql('DROP TABLE stat_task');
        $this->addSql('ALTER TABLE task CHANGE sous_taches sous_taches JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE `user` ADD stats LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
