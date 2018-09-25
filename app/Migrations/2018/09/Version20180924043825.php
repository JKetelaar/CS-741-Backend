<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180924043825 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', guest_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_order_items (cart_id INT NOT NULL, orderitem_id INT NOT NULL, INDEX IDX_315364F01AD5CDBF (cart_id), INDEX IDX_315364F028D3A508 (orderitem_id), PRIMARY KEY(cart_id, orderitem_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_52EA1F094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_order_items ADD CONSTRAINT FK_315364F01AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_order_items ADD CONSTRAINT FK_315364F028D3A508 FOREIGN KEY (orderitem_id) REFERENCES order_item (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart_order_items DROP FOREIGN KEY FK_315364F01AD5CDBF');
        $this->addSql('ALTER TABLE cart_order_items DROP FOREIGN KEY FK_315364F028D3A508');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_order_items');
        $this->addSql('DROP TABLE order_item');
    }
}
