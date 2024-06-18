<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240618090853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wishes_user DROP FOREIGN KEY FK_19E0D05A3510ADCE');
        $this->addSql('ALTER TABLE wishes_user DROP FOREIGN KEY FK_19E0D05AA76ED395');
        $this->addSql('DROP TABLE wishes_user');
        $this->addSql('ALTER TABLE wishes ADD user_id INT DEFAULT NULL, ADD wishname VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE wishes ADD CONSTRAINT FK_DD0FA368A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DD0FA368A76ED395 ON wishes (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wishes_user (wishes_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_19E0D05A3510ADCE (wishes_id), INDEX IDX_19E0D05AA76ED395 (user_id), PRIMARY KEY(wishes_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE wishes_user ADD CONSTRAINT FK_19E0D05A3510ADCE FOREIGN KEY (wishes_id) REFERENCES wishes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishes_user ADD CONSTRAINT FK_19E0D05AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishes DROP FOREIGN KEY FK_DD0FA368A76ED395');
        $this->addSql('DROP INDEX IDX_DD0FA368A76ED395 ON wishes');
        $this->addSql('ALTER TABLE wishes DROP user_id, DROP wishname');
    }
}
