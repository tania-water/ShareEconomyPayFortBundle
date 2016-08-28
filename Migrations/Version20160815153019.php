<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160815153019 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pf_payment_method (id INT UNSIGNED AUTO_INCREMENT NOT NULL, holder_id INT UNSIGNED NOT NULL, card_holder_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, card_number VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, expiry_date DATE NOT NULL, card_bin INT DEFAULT NULL, token_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, pf_status SMALLINT DEFAULT NULL, created_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pf_payment_method');
    }
}
