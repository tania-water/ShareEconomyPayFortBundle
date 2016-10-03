<?php

namespace Ibtikar\ShareEconomyPayFortBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161003142830 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX token_name ON pf_payment_method (token_name)');
        $this->addSql('CREATE UNIQUE INDEX fort_id ON pf_payment_method (fort_id)');
        $this->addSql('CREATE UNIQUE INDEX merchant_reference ON pf_payment_method (merchant_reference)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX token_name ON pf_payment_method');
        $this->addSql('DROP INDEX fort_id ON pf_payment_method');
        $this->addSql('DROP INDEX merchant_reference ON pf_payment_method');
    }
}
