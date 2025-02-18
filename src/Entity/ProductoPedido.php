<?php

namespace App\Entity;

use App\Repository\ProductoPedidoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductoPedidoRepository::class)]
class ProductoPedido{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $cantidad;

    #[ORM\Column]
    private float $precio;

    #[ORM\ManyToOne(targetEntity: Pedido::class, inversedBy: 'productosPedidos')]
    #[ORM\JoinColumn(nullable: false)]
    private Pedido $pedido;

    #[ORM\ManyToOne(targetEntity: Producto::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Producto $producto;



    public function __construct()
    {

    }

    public function getId(){
        return $this->id;
    }

    public function getCantidad(){
        return $this->cantidad;
    }
    public function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }

    public function getPrecio(){
        return $this->precio;
    }
    public function setPrecio($precio){
        $this->precio = $precio;
    }

    public function getPedido(): Pedido{
        return $this->pedido;
    }
    public function setPedido(Pedido $pedido): static{
        $this->pedido = $pedido;
        return $this;
    }

    public function getProducto(): Producto{
        return $this->producto;
    }
    public function setProducto(Producto $producto): static{
        $this->producto = $producto;
        return $this;
    }



}