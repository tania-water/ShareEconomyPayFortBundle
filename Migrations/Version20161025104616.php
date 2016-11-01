<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161025104616 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pf_transaction (id INT AUTO_INCREMENT NOT NULL, payment_method_id INT NOT NULL, invoice_id INT NOT NULL, customer_ip VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_general_ci, fort_id VARCHAR(20) NOT NULL COLLATE utf8mb4_general_ci, currency CHAR(3) NOT NULL COLLATE utf8mb4_general_ci, amount NUMERIC(5, 2) NOT NULL, merchant_reference VARCHAR(100) NOT NULL COLLATE utf8mb4_general_ci, authorization_code INT DEFAULT NULL, current_status INT DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, INDEX payment_method_id (payment_method_id), INDEX invoice_id (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pf_transaction_status (id INT AUTO_INCREMENT NOT NULL, transaction_id INT NOT NULL, response_code SMALLINT NOT NULL, response_message VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, status SMALLINT NOT NULL, created_at DATETIME DEFAULT NULL, INDEX transaction_id (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pf_transaction ADD CONSTRAINT pf_transaction_ibfk_1 FOREIGN KEY (payment_method_id) REFERENCES pf_payment_method (id)');
        $this->addSql('ALTER TABLE pf_transaction_status ADD CONSTRAINT pf_transaction_status_ibfk_1 FOREIGN KEY (transaction_id) REFERENCES pf_transaction (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pf_transaction_status DROP FOREIGN KEY pf_transaction_status_ibfk_1');
        $this->addSql('DROP TABLE pf_transaction');
        $this->addSql('DROP TABLE pf_transaction_status');
    }
}