<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181116195042 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purchase ADD billing_address_id INT DEFAULT NULL, ADD shipping_address_id INT DEFAULT NULL, ADD state VARCHAR(255) DEFAULT NULL, DROP billing_address, DROP shipping_address');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES order_address (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B4D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES order_address (id)');
        $this->addSql('CREATE INDEX IDX_6117D13B79D0C0E4 ON purchase (billing_address_id)');
        $this->addSql('CREATE INDEX IDX_6117D13B4D4CFF2B ON purchase (shipping_address_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B79D0C0E4');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B4D4CFF2B');
        $this->addSql('DROP INDEX IDX_6117D13B79D0C0E4 ON purchase');
        $this->addSql('DROP INDEX IDX_6117D13B4D4CFF2B ON purchase');
        $this->addSql('ALTER TABLE purchase ADD billing_address VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD shipping_address VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP billing_address_id, DROP shipping_address_id, DROP state');
    }
}
