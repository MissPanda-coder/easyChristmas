<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614074757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE draw DROP FOREIGN KEY FK_70F2BD0F876C4DDA');
        $this->addSql('DROP INDEX IDX_70F2BD0F876C4DDA ON draw');
        $this->addSql('ALTER TABLE draw DROP organizer_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE draw ADD organizer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE draw ADD CONSTRAINT FK_70F2BD0F876C4DDA FOREIGN KEY (organizer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_70F2BD0F876C4DDA ON draw (organizer_id)');
    }
}
