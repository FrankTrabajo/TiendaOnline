<?php

namespace App\Entity;

use App\Repository\PedidoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidoRepository::class)]
class Pedido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaPedido = null;

    #[ORM\Column]
    private ?float $precioTotal = null;

    #[ORM\ManyToOne(inversedBy: 'pedidos')]
    private ?Usuario $usuario = null;

    #[ORM\OneToMany(targetEntity: ProductoPedido::class, mappedBy: 'pedido')]
    private Collection $productosPedidos;



    public function __construct()
    {
        $this->productosPedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaPedido(): ?\DateTimeInterface
    {
        return $this->fechaPedido;
    }

    public function setFechaPedido(\DateTimeInterface $fechaPedido): static
    {
        $this->fechaPedido = $fechaPedido;

        return $this;
    }

    public function getPrecioTotal(): ?float
    {
        return $this->precioTotal;
    }

    public function setPrecioTotal(float $precioTotal): static
    {
        $this->precioTotal = $precioTotal;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getProductosPedidos(): Collection
    {
        return $this->productosPedidos;
    }


}
