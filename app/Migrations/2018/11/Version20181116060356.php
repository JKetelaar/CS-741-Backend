<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181116060356 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purchase ADD billing_address_id INT DEFAULT NULL, ADD shipping_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES order_address (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B4D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES order_address (id)');
        $this->addSql('CREATE INDEX IDX_6117D13B79D0C0E4 ON purchase (billing_address_id)');
        $this->addSql('CREATE INDEX IDX_6117D13B4D4CFF2B ON purchase (shipping_address_id)');
        $this->addSql('ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CAD20B7A04');
        $this->addSql('ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CAD9E4391A');
        $this->addSql('DROP INDEX IDX_FB34C6CAD20B7A04 ON order_address');
        $this->addSql('DROP INDEX IDX_FB34C6CAD9E4391A ON order_address');
        $this->addSql('ALTER TABLE order_address DROP billing_purchase_id, DROP shipping_purchase_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_address ADD billing_purchase_id INT DEFAULT NULL, ADD shipping_purchase_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CAD20B7A04 FOREIGN KEY (billing_purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CAD9E4391A FOREIGN KEY (shipping_purchase_id) REFERENCES purchase (id)');
        $this->addSql('CREATE INDEX IDX_FB34C6CAD20B7A04 ON order_address (billing_purchase_id)');
        $this->addSql('CREATE INDEX IDX_FB34C6CAD9E4391A ON order_address (shipping_purchase_id)');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B79D0C0E4');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B4D4CFF2B');
        $this->addSql('DROP INDEX IDX_6117D13B79D0C0E4 ON purchase');
        $this->addSql('DROP INDEX IDX_6117D13B4D4CFF2B ON purchase');
        $this->addSql('ALTER TABLE purchase DROP billing_address_id, DROP shipping_address_id');
    }
}
