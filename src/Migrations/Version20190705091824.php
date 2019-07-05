<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190705091824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pictureslink ADD figure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pictureslink ADD CONSTRAINT FK_8D7DDE615C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('CREATE INDEX IDX_8D7DDE615C011B5 ON pictureslink (figure_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pictureslink DROP FOREIGN KEY FK_8D7DDE615C011B5');
        $this->addSql('DROP INDEX IDX_8D7DDE615C011B5 ON pictureslink');
        $this->addSql('ALTER TABLE pictureslink DROP figure_id');
    }
}
