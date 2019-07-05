<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190705092512 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pictureslink ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pictureslink ADD CONSTRAINT FK_8D7DDE61A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D7DDE61A76ED395 ON pictureslink (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pictureslink DROP FOREIGN KEY FK_8D7DDE61A76ED395');
        $this->addSql('DROP INDEX IDX_8D7DDE61A76ED395 ON pictureslink');
        $this->addSql('ALTER TABLE pictureslink DROP user_id');
    }
}
