<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614075602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assignation ADD draw_id INT NOT NULL, ADD user_giver_id INT NOT NULL, ADD user_receiver_id INT NOT NULL');
        $this->addSql('ALTER TABLE assignation ADD CONSTRAINT FK_D2A03CE06FC5C1B8 FOREIGN KEY (draw_id) REFERENCES draw (id)');
        $this->addSql('ALTER TABLE assignation ADD CONSTRAINT FK_D2A03CE09530F929 FOREIGN KEY (user_giver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE assignation ADD CONSTRAINT FK_D2A03CE064482423 FOREIGN KEY (user_receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D2A03CE06FC5C1B8 ON assignation (draw_id)');
        $this->addSql('CREATE INDEX IDX_D2A03CE09530F929 ON assignation (user_giver_id)');
        $this->addSql('CREATE INDEX IDX_D2A03CE064482423 ON assignation (user_receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assignation DROP FOREIGN KEY FK_D2A03CE06FC5C1B8');
        $this->addSql('ALTER TABLE assignation DROP FOREIGN KEY FK_D2A03CE09530F929');
        $this->addSql('ALTER TABLE assignation DROP FOREIGN KEY FK_D2A03CE064482423');
        $this->addSql('DROP INDEX IDX_D2A03CE06FC5C1B8 ON assignation');
        $this->addSql('DROP INDEX IDX_D2A03CE09530F929 ON assignation');
        $this->addSql('DROP INDEX IDX_D2A03CE064482423 ON assignation');
        $this->addSql('ALTER TABLE assignation DROP draw_id, DROP user_giver_id, DROP user_receiver_id');
    }
}
