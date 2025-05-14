<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250508075658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trophy (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(25) NOT NULL, description VARCHAR(255) NOT NULL, rarity VARCHAR(10) NOT NULL, INDEX IDX_112AFAE912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trophy_user (trophy_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7AAA1519F59AEEEF (trophy_id), INDEX IDX_7AAA1519A76ED395 (user_id), PRIMARY KEY(trophy_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trophy ADD CONSTRAINT FK_112AFAE912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE trophy_user ADD CONSTRAINT FK_7AAA1519F59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trophy_user ADD CONSTRAINT FK_7AAA1519A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trophy DROP FOREIGN KEY FK_112AFAE912469DE2');
        $this->addSql('ALTER TABLE trophy_user DROP FOREIGN KEY FK_7AAA1519F59AEEEF');
        $this->addSql('ALTER TABLE trophy_user DROP FOREIGN KEY FK_7AAA1519A76ED395');
        $this->addSql('DROP TABLE trophy');
        $this->addSql('DROP TABLE trophy_user');
    }
}
