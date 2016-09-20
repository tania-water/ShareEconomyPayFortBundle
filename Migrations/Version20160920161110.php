<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160920161110 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pf_payment_method CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE holder_id holder_id INT NOT NULL');
        $this->addSql('CREATE INDEX holder_id ON pf_payment_method (holder_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX holder_id ON pf_payment_method');
        $this->addSql('ALTER TABLE pf_payment_method CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE holder_id holder_id INT UNSIGNED NOT NULL');
    }
}
