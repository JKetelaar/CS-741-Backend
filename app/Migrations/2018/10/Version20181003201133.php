<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181003201133 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F0928D3A508');
        $this->addSql('DROP INDEX IDX_52EA1F0928D3A508 ON order_item');
        $this->addSql('ALTER TABLE order_item CHANGE orderitem_id cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F091AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F091AD5CDBF ON order_item (cart_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F091AD5CDBF');
        $this->addSql('DROP INDEX IDX_52EA1F091AD5CDBF ON order_item');
        $this->addSql('ALTER TABLE order_item CHANGE cart_id orderitem_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F0928D3A508 FOREIGN KEY (orderitem_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F0928D3A508 ON order_item (orderitem_id)');
    }
}
