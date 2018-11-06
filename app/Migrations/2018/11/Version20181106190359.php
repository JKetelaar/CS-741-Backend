<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181106190359 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B71AD5CDBF');
        $this->addSql('DROP INDEX UNIQ_BA388B71AD5CDBF ON cart');
        $this->addSql('ALTER TABLE cart CHANGE cart_id promotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7139DF194 ON cart (promotion_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7139DF194');
        $this->addSql('DROP INDEX UNIQ_BA388B7139DF194 ON cart');
        $this->addSql('ALTER TABLE cart CHANGE promotion_id cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B71AD5CDBF FOREIGN KEY (cart_id) REFERENCES promotion (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B71AD5CDBF ON cart (cart_id)');
    }
}
