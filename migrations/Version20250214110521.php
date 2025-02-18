<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214110521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedido DROP FOREIGN KEY FK_C4EC16CEBEC5D010');
        $this->addSql('DROP INDEX IDX_C4EC16CEBEC5D010 ON pedido');
        $this->addSql('ALTER TABLE pedido DROP producto_pedido_id');
        $this->addSql('ALTER TABLE producto DROP FOREIGN KEY FK_A7BB0615BEC5D010');
        $this->addSql('DROP INDEX IDX_A7BB0615BEC5D010 ON producto');
        $this->addSql('ALTER TABLE producto DROP producto_pedido_id');
        $this->addSql('ALTER TABLE producto_pedido ADD pedido_id INT NOT NULL, ADD producto_id INT NOT NULL');
        $this->addSql('ALTER TABLE producto_pedido ADD CONSTRAINT FK_69CBB9804854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id)');
        $this->addSql('ALTER TABLE producto_pedido ADD CONSTRAINT FK_69CBB9807645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('CREATE INDEX IDX_69CBB9804854653A ON producto_pedido (pedido_id)');
        $this->addSql('CREATE INDEX IDX_69CBB9807645698E ON producto_pedido (producto_id)');
        $this->addSql('ALTER TABLE usuario DROP name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedido ADD producto_pedido_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pedido ADD CONSTRAINT FK_C4EC16CEBEC5D010 FOREIGN KEY (producto_pedido_id) REFERENCES producto_pedido (id)');
        $this->addSql('CREATE INDEX IDX_C4EC16CEBEC5D010 ON pedido (producto_pedido_id)');
        $this->addSql('ALTER TABLE producto ADD producto_pedido_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE producto ADD CONSTRAINT FK_A7BB0615BEC5D010 FOREIGN KEY (producto_pedido_id) REFERENCES producto_pedido (id)');
        $this->addSql('CREATE INDEX IDX_A7BB0615BEC5D010 ON producto (producto_pedido_id)');
        $this->addSql('ALTER TABLE producto_pedido DROP FOREIGN KEY FK_69CBB9804854653A');
        $this->addSql('ALTER TABLE producto_pedido DROP FOREIGN KEY FK_69CBB9807645698E');
        $this->addSql('DROP INDEX IDX_69CBB9804854653A ON producto_pedido');
        $this->addSql('DROP INDEX IDX_69CBB9807645698E ON producto_pedido');
        $this->addSql('ALTER TABLE producto_pedido DROP pedido_id, DROP producto_id');
        $this->addSql('ALTER TABLE usuario ADD name VARCHAR(255) NOT NULL');
    }
}
