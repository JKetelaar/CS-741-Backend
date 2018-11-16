<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181116053238 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purchase DROP billing_address, DROP shipping_address');
        $this->addSql('ALTER TABLE order_address ADD purchase_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('CREATE INDEX IDX_FB34C6CA558FBEB9 ON order_address (purchase_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA558FBEB9');
        $this->addSql('DROP INDEX IDX_FB34C6CA558FBEB9 ON order_address');
        $this->addSql('ALTER TABLE order_address DROP purchase_id');
        $this->addSql('ALTER TABLE purchase ADD billing_address VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD shipping_address VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
