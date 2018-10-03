<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181003200939 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE cart_order_items');
        $this->addSql('ALTER TABLE order_item ADD orderitem_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F0928D3A508 FOREIGN KEY (orderitem_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F0928D3A508 ON order_item (orderitem_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cart_order_items (cart_id INT NOT NULL, orderitem_id INT NOT NULL, INDEX IDX_315364F01AD5CDBF (cart_id), INDEX IDX_315364F028D3A508 (orderitem_id), PRIMARY KEY(cart_id, orderitem_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_order_items ADD CONSTRAINT FK_315364F01AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_order_items ADD CONSTRAINT FK_315364F028D3A508 FOREIGN KEY (orderitem_id) REFERENCES order_item (id)');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F0928D3A508');
        $this->addSql('DROP INDEX IDX_52EA1F0928D3A508 ON order_item');
        $this->addSql('ALTER TABLE order_item DROP orderitem_id');
    }
}
