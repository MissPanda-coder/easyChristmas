<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603152522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assignation (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignation_has_wishes (assignation_id INT NOT NULL, wishes_id INT NOT NULL, INDEX IDX_670076176A86CF55 (assignation_id), INDEX IDX_670076173510ADCE (wishes_id), PRIMARY KEY(assignation_id, wishes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE draw (id INT AUTO_INCREMENT NOT NULL, organizer_id INT DEFAULT NULL, drawdate DATETIME NOT NULL, drawyear INT NOT NULL, INDEX IDX_70F2BD0F876C4DDA (organizer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE draw_user (draw_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2481798C6FC5C1B8 (draw_id), INDEX IDX_2481798CA76ED395 (user_id), PRIMARY KEY(draw_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exclusion (id INT AUTO_INCREMENT NOT NULL, draw_id INT DEFAULT NULL, userparticipant_id INT DEFAULT NULL, userexcluded_id INT DEFAULT NULL, INDEX IDX_DF1686C6FC5C1B8 (draw_id), INDEX IDX_DF1686CDCC2DD67 (userparticipant_id), INDEX IDX_DF1686CD8BF4670 (userexcluded_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, unit_id INT NOT NULL, ingredientname VARCHAR(100) NOT NULL, INDEX IDX_6BAF7870F8BD700D (unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, recipecategory_id INT DEFAULT NULL, recipedifficulty_id INT DEFAULT NULL, title VARCHAR(120) NOT NULL, description VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, duration INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) NOT NULL, INDEX IDX_DA88B137A76ED395 (user_id), INDEX IDX_DA88B1373873EBF9 (recipecategory_id), INDEX IDX_DA88B137ECAAA808 (recipedifficulty_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_has_ingredient (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, ingredient_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_FF7A137059D8A214 (recipe_id), INDEX IDX_FF7A1370933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipecategory (id INT AUTO_INCREMENT NOT NULL, categoryname VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipedifficulty (id INT AUTO_INCREMENT NOT NULL, difficultyname VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipestep (id INT AUTO_INCREMENT NOT NULL, recipe_id INT DEFAULT NULL, stepnumber INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_7F85838A59D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, expired_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B9983CE5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, rolename VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_332CA4DDD60322AC (role_id), INDEX IDX_332CA4DDA76ED395 (user_id), PRIMARY KEY(role_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, unitname VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, photo VARCHAR(255) DEFAULT NULL, username VARCHAR(150) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, verification_token VARCHAR(255) DEFAULT NULL, verification_token_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishes (id INT AUTO_INCREMENT NOT NULL, wishesyear INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishes_user (wishes_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_19E0D05A3510ADCE (wishes_id), INDEX IDX_19E0D05AA76ED395 (user_id), PRIMARY KEY(wishes_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignation_has_wishes ADD CONSTRAINT FK_670076176A86CF55 FOREIGN KEY (assignation_id) REFERENCES assignation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignation_has_wishes ADD CONSTRAINT FK_670076173510ADCE FOREIGN KEY (wishes_id) REFERENCES wishes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE draw ADD CONSTRAINT FK_70F2BD0F876C4DDA FOREIGN KEY (organizer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE draw_user ADD CONSTRAINT FK_2481798C6FC5C1B8 FOREIGN KEY (draw_id) REFERENCES draw (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE draw_user ADD CONSTRAINT FK_2481798CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exclusion ADD CONSTRAINT FK_DF1686C6FC5C1B8 FOREIGN KEY (draw_id) REFERENCES draw (id)');
        $this->addSql('ALTER TABLE exclusion ADD CONSTRAINT FK_DF1686CDCC2DD67 FOREIGN KEY (userparticipant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exclusion ADD CONSTRAINT FK_DF1686CD8BF4670 FOREIGN KEY (userexcluded_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B1373873EBF9 FOREIGN KEY (recipecategory_id) REFERENCES recipecategory (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137ECAAA808 FOREIGN KEY (recipedifficulty_id) REFERENCES recipedifficulty (id)');
        $this->addSql('ALTER TABLE recipe_has_ingredient ADD CONSTRAINT FK_FF7A137059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_has_ingredient ADD CONSTRAINT FK_FF7A1370933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE recipestep ADD CONSTRAINT FK_7F85838A59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE reset_password ADD CONSTRAINT FK_B9983CE5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishes_user ADD CONSTRAINT FK_19E0D05A3510ADCE FOREIGN KEY (wishes_id) REFERENCES wishes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishes_user ADD CONSTRAINT FK_19E0D05AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assignation_has_wishes DROP FOREIGN KEY FK_670076176A86CF55');
        $this->addSql('ALTER TABLE assignation_has_wishes DROP FOREIGN KEY FK_670076173510ADCE');
        $this->addSql('ALTER TABLE draw DROP FOREIGN KEY FK_70F2BD0F876C4DDA');
        $this->addSql('ALTER TABLE draw_user DROP FOREIGN KEY FK_2481798C6FC5C1B8');
        $this->addSql('ALTER TABLE draw_user DROP FOREIGN KEY FK_2481798CA76ED395');
        $this->addSql('ALTER TABLE exclusion DROP FOREIGN KEY FK_DF1686C6FC5C1B8');
        $this->addSql('ALTER TABLE exclusion DROP FOREIGN KEY FK_DF1686CDCC2DD67');
        $this->addSql('ALTER TABLE exclusion DROP FOREIGN KEY FK_DF1686CD8BF4670');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870F8BD700D');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137A76ED395');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B1373873EBF9');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137ECAAA808');
        $this->addSql('ALTER TABLE recipe_has_ingredient DROP FOREIGN KEY FK_FF7A137059D8A214');
        $this->addSql('ALTER TABLE recipe_has_ingredient DROP FOREIGN KEY FK_FF7A1370933FE08C');
        $this->addSql('ALTER TABLE recipestep DROP FOREIGN KEY FK_7F85838A59D8A214');
        $this->addSql('ALTER TABLE reset_password DROP FOREIGN KEY FK_B9983CE5A76ED395');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('ALTER TABLE wishes_user DROP FOREIGN KEY FK_19E0D05A3510ADCE');
        $this->addSql('ALTER TABLE wishes_user DROP FOREIGN KEY FK_19E0D05AA76ED395');
        $this->addSql('DROP TABLE assignation');
        $this->addSql('DROP TABLE assignation_has_wishes');
        $this->addSql('DROP TABLE draw');
        $this->addSql('DROP TABLE draw_user');
        $this->addSql('DROP TABLE exclusion');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_has_ingredient');
        $this->addSql('DROP TABLE recipecategory');
        $this->addSql('DROP TABLE recipedifficulty');
        $this->addSql('DROP TABLE recipestep');
        $this->addSql('DROP TABLE reset_password');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wishes');
        $this->addSql('DROP TABLE wishes_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
