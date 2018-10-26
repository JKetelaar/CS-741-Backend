<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181026182412 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_address ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CAA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_FB34C6CAA76ED395 ON order_address (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CAA76ED395');
        $this->addSql('DROP INDEX IDX_FB34C6CAA76ED395 ON order_address');
        $this->addSql('ALTER TABLE order_address DROP user_id');
    }
}
