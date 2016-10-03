<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161003134306 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pf_payment_method ADD fort_id VARCHAR(255) NOT NULL AFTER token_name, ADD merchant_reference VARCHAR(50) NOT NULL AFTER token_name, ADD payment_option VARCHAR(50) DEFAULT NULL AFTER token_name, DROP card_holder_name, DROP card_bin, DROP pf_status, CHANGE expiry_date expiry_date VARCHAR(10) NOT NULL, CHANGE token_name token_name VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pf_payment_method ADD card_holder_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD card_bin INT DEFAULT NULL, ADD pf_status SMALLINT DEFAULT NULL, DROP fort_id, DROP merchant_reference, DROP payment_option, CHANGE expiry_date expiry_date DATE NOT NULL, CHANGE token_name token_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
