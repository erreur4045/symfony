<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190729095035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments ADD idfigure_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A7BDBB840 FOREIGN KEY (idfigure_id) REFERENCES figure (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A7BDBB840 ON comments (idfigure_id)');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37A35C06029');
        $this->addSql('DROP INDEX IDX_2F57B37A35C06029 ON figure');
        $this->addSql('ALTER TABLE figure DROP idcomment_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A7BDBB840');
        $this->addSql('DROP INDEX IDX_5F9E962A7BDBB840 ON comments');
        $this->addSql('ALTER TABLE comments DROP idfigure_id');
        $this->addSql('ALTER TABLE figure ADD idcomment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37A35C06029 FOREIGN KEY (idcomment_id) REFERENCES comments (id)');
        $this->addSql('CREATE INDEX IDX_2F57B37A35C06029 ON figure (idcomment_id)');
    }
}
