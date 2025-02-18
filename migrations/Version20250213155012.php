<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213155012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedido ADD producto_pedido_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pedido ADD CONSTRAINT FK_C4EC16CEBEC5D010 FOREIGN KEY (producto_pedido_id) REFERENCES producto_pedido (id)');
        $this->addSql('CREATE INDEX IDX_C4EC16CEBEC5D010 ON pedido (producto_pedido_id)');
        $this->addSql('ALTER TABLE producto ADD producto_pedido_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE producto ADD CONSTRAINT FK_A7BB0615BEC5D010 FOREIGN KEY (producto_pedido_id) REFERENCES producto_pedido (id)');
        $this->addSql('CREATE INDEX IDX_A7BB0615BEC5D010 ON producto (producto_pedido_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedido DROP FOREIGN KEY FK_C4EC16CEBEC5D010');
        $this->addSql('DROP INDEX IDX_C4EC16CEBEC5D010 ON pedido');
        $this->addSql('ALTER TABLE pedido DROP producto_pedido_id');
        $this->addSql('ALTER TABLE producto DROP FOREIGN KEY FK_A7BB0615BEC5D010');
        $this->addSql('DROP INDEX IDX_A7BB0615BEC5D010 ON producto');
        $this->addSql('ALTER TABLE producto DROP producto_pedido_id');
    }
}
