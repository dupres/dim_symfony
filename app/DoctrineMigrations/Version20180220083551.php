<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180220083551 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_show ADD show_id INT DEFAULT NULL, DROP author');
        $this->addSql('ALTER TABLE s_show ADD CONSTRAINT FK_957D80CBD0C1FC64 FOREIGN KEY (show_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_957D80CBD0C1FC64 ON s_show (show_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE s_show DROP FOREIGN KEY FK_957D80CBD0C1FC64');
        $this->addSql('DROP INDEX IDX_957D80CBD0C1FC64 ON s_show');
        $this->addSql('ALTER TABLE s_show ADD author INT NOT NULL, DROP show_id');
    }
}
