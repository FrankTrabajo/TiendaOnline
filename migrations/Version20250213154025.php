<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213154025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producto_pedido DROP FOREIGN KEY FK_69CBB9807645698E');
        $this->addSql('ALTER TABLE producto_pedido DROP FOREIGN KEY FK_69CBB9804854653A');
        $this->addSql('DROP INDEX IDX_69CBB9807645698E ON producto_pedido');
        $this->addSql('DROP INDEX IDX_69CBB9804854653A ON producto_pedido');
        $this->addSql('ALTER TABLE producto_pedido ADD id INT AUTO_INCREMENT NOT NULL, ADD cantidad INT NOT NULL, ADD precio DOUBLE PRECISION NOT NULL, DROP producto_id, DROP pedido_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producto_pedido MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON producto_pedido');
        $this->addSql('ALTER TABLE producto_pedido ADD pedido_id INT NOT NULL, DROP id, DROP precio, CHANGE cantidad producto_id INT NOT NULL');
        $this->addSql('ALTER TABLE producto_pedido ADD CONSTRAINT FK_69CBB9807645698E FOREIGN KEY (producto_id) REFERENCES producto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producto_pedido ADD CONSTRAINT FK_69CBB9804854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_69CBB9807645698E ON producto_pedido (producto_id)');
        $this->addSql('CREATE INDEX IDX_69CBB9804854653A ON producto_pedido (pedido_id)');
        $this->addSql('ALTER TABLE producto_pedido ADD PRIMARY KEY (producto_id, pedido_id)');
    }
}
