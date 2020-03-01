<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200301230228 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, idfigure_id INT NOT NULL, user_id INT NOT NULL, text VARCHAR(1024) NOT NULL, datecreate DATETIME NOT NULL, dateupdate DATETIME DEFAULT NULL, INDEX IDX_5F9E962A7BDBB840 (idfigure_id), INDEX IDX_5F9E962AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figure (id INT AUTO_INCREMENT NOT NULL, idfiguregroup_id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, description VARCHAR(512) NOT NULL, datecreate DATETIME DEFAULT NULL, dateupdate DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_2F57B37A989D9B62 (slug), INDEX IDX_2F57B37A913B10F3 (idfiguregroup_id), INDEX IDX_2F57B37AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figuregroup (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pictureslink (id INT AUTO_INCREMENT NOT NULL, figure_id INT DEFAULT NULL, linkpictures VARCHAR(512) NOT NULL, first_image TINYINT(1) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, INDEX IDX_8D7DDE615C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, datesub DATETIME NOT NULL, password VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, roles JSON NOT NULL, profile_picture VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videolink (id INT AUTO_INCREMENT NOT NULL, figure_id INT NOT NULL, linkvideo VARCHAR(512) NOT NULL, INDEX IDX_815E450F5C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A7BDBB840 FOREIGN KEY (idfigure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37A913B10F3 FOREIGN KEY (idfiguregroup_id) REFERENCES figuregroup (id)');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pictureslink ADD CONSTRAINT FK_8D7DDE615C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE videolink ADD CONSTRAINT FK_815E450F5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A7BDBB840');
        $this->addSql('ALTER TABLE pictureslink DROP FOREIGN KEY FK_8D7DDE615C011B5');
        $this->addSql('ALTER TABLE videolink DROP FOREIGN KEY FK_815E450F5C011B5');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37A913B10F3');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37AA76ED395');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE figure');
        $this->addSql('DROP TABLE figuregroup');
        $this->addSql('DROP TABLE pictureslink');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE videolink');
    }
}
