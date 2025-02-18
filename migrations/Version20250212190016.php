<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212190016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE producto_pedido (producto_id INT NOT NULL, pedido_id INT NOT NULL, INDEX IDX_69CBB9807645698E (producto_id), INDEX IDX_69CBB9804854653A (pedido_id), PRIMARY KEY(producto_id, pedido_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE producto_pedido ADD CONSTRAINT FK_69CBB9807645698E FOREIGN KEY (producto_id) REFERENCES producto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producto_pedido ADD CONSTRAINT FK_69CBB9804854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pedido ADD usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pedido ADD CONSTRAINT FK_C4EC16CEDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_C4EC16CEDB38439E ON pedido (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producto_pedido DROP FOREIGN KEY FK_69CBB9807645698E');
        $this->addSql('ALTER TABLE producto_pedido DROP FOREIGN KEY FK_69CBB9804854653A');
        $this->addSql('DROP TABLE producto_pedido');
        $this->addSql('ALTER TABLE pedido DROP FOREIGN KEY FK_C4EC16CEDB38439E');
        $this->addSql('DROP INDEX IDX_C4EC16CEDB38439E ON pedido');
        $this->addSql('ALTER TABLE pedido DROP usuario_id');
    }
}
