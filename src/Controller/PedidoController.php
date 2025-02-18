<?php

namespace App\Controller;

use App\Entity\Pedido;
use App\Entity\Producto;
use App\Entity\ProductoPedido;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class PedidoController extends AbstractController
{
    #[Route('/pedido', name: 'app_pedido')]
    public function index(): Response
    {
        return $this->render('pedido/index.html.twig', [
            'controller_name' => 'PedidoController',
        ]);
    }

    #[Route('/ver-pedido', name: 'ver_pedido')]
    public function verPedido(EntityManagerInterface $entityManager): Response{

        $usuario = $this->getUser();
        if(!$usuario){
            return $this->redirectToRoute('app_login');
        }

        $pedido = $entityManager->getRepository(Pedido::class)->findOneBy([
            'usuario' => $usuario
        ]);

        if(!$pedido){
            return $this->render('pedido/ver.html.twig', [
                'pedido' => null,
                'productos' => []
            ]);
        }

        //Obtener los productos del pedido
        $productosPedido = $pedido->getProductosPedidos();
        $productos = [];

        foreach($productosPedido as $productoPedido){
            $productos[] = [
                'titulo' => $productoPedido->getProducto()->getTitulo(),
                'producto' => $productoPedido->getProducto(),
                'cantidad' => $productoPedido->getCantidad(),
                'precio' => $productoPedido->getPrecio(),
                'id' => $productoPedido->getProducto()->getId()
            ];
        }

        if(empty($productos)){
            $productos = [];
        }

        return $this->render('pedido/ver.html.twig', [
            'pedido' => $pedido,
            'productos' => $productos,
        ]);



    }


    #[Route('/anadir-pedido/{id}', name: 'add_pedido', methods: ['POST'])]
    public function anadirProducto($id, EntityManagerInterface $entityManager, SessionInterface $sesion): Response{
        //Verifico si el usuario está autenticado
        //Obtengo el usuario actual
        $usuario = $this->getUser();
        //Si no hay ningun usuario nos dara un error
        if(!$usuario){
            return new JsonResponse(['status' => 400, 'message' =>'usuario no autenticado' ]);
        }

        //Aqui obtengo el producto que quiero meter en el pedido
        $producto = $entityManager->getRepository(Producto::class)->find($id);
        //Si no encuentro ningun producto salta un error
        if(!$producto){
            return new JsonResponse(['status' => 400, 'message' => 'producto no encontrado']);
        }

        //Obtengo el pedido que está asignado al usuario lo que hago es buscandolo por usuario
        $pedido = $entityManager->getRepository(Pedido::class)->findOneBy([
            'usuario' => $usuario,
        ]);

        //Si no hay ningun pedido, creo uno nuevo y lo subo a la base de datos
        if(!$pedido){
            $pedido = new Pedido();
            $pedido->setFechaPedido(new \DateTime());
            $pedido->setPrecioTotal(0);
            $pedido->setUsuario($usuario);

            $entityManager->persist($pedido);
            $entityManager->flush();

            $sesion->set('pedido_id', $pedido->getId());
        }

        //Verifico si ya existe el producto
        //Aqui obtengo un objeto de la clase productoPedido que tenga como id pedido el pedido y producto
        $productoPedido = $entityManager->getRepository(ProductoPedido::class)->findOneBy([
            'pedido'=>$pedido,
            'producto'=>$producto
        ]);

        //Si no encuentra ningun productoPedido creo uno nuevo desde 0
        //Añadiendo el producto anterior en el productoPedido
        //Y añadiendo el pedido que tiene asignado el usuario
        if(!$productoPedido){
            $productoPedido = new ProductoPedido();
            $productoPedido->setPedido($pedido);
            $productoPedido->setProducto($producto);
            $productoPedido->setCantidad(1);
            $productoPedido->setPrecio($producto->getPrecio());

            $entityManager->persist($productoPedido);
        }else{
            //Si ya existe
            //Agregar producto a productoPedido
            $productoPedido->setProducto($producto);
            //Aumento +1 en cantidad
            $productoPedido->setCantidad($productoPedido->getCantidad() + 1);
            //Aumento el precio del productoPedudo
            $productoPedido->setPrecio($productoPedido->getPrecio() + $producto->getPrecio());

        }


        $precioPedido = $pedido->getPrecioTotal();
        $precioActualizado = $precioPedido + $producto->getPrecio();
        //Actualizo el precio total del pedido
        $pedido->setPrecioTotal($precioActualizado);

        $entityManager->flush();

        return $this->redirectToRoute('ver_pedido');

    }

    #[Route('/delete-pedido/{id}', name: 'eliminar_pedido')]
    public function eliminarPedido($id, EntityManagerInterface $entityManager): Response {

        //Obtengo el producto que quiero eliminar
        $producto = $entityManager->getRepository(Producto::class)->find($id);
        //Si el producto no existe da un error
        if(!$producto){
            return new JsonResponse(['status' => 400, 'message' => 'producto no encontrado']);
        }

        //Lo que tengo que hacer es restarle 1 al producto de ProductoPedido con la misma ID
        //Debo obtener el usuario para saber el id del pedido
        $usuario = $this->getUser();
        //Si no hay ningun usuario nos dara un error
        if(!$usuario){
            return $this->redirectToRoute('login');
        }

        //Ahora debemos obtener el pedido
        $pedido = $entityManager->getRepository(Pedido::class)->findOneBy([
            'usuario' => $usuario
        ]);

        //Ahora una vez tenga el pedido y el producto, buscaré el productoPedido
        $productoPedido = $entityManager->getRepository(ProductoPedido::class)->findOneBy([
            'pedido' => $pedido,
            'producto' => $producto
        ]);

        //Ahora lo que hacemos es restar 1 en la cantidad
        //Primero comprobamos si la cantidad actual es 1, para eliminarlo entero
        if($productoPedido->getCantidad() == 1){
            $entityManager->remove($productoPedido);
            $entityManager->flush();
        }else{
            //Le resto 1 en cantidad
            $productoPedido->setCantidad($productoPedido->getCantidad() - 1);
            //Resto el precio del productoPedudo
            $precioAct = $productoPedido->getPrecio() - $producto->getPrecio();
            $productoPedido->setPrecio($precioAct);
        }

        $precioPedido = $pedido->getPrecioTotal();
        $precioActualizado = $precioPedido - $producto->getPrecio();
        //Actualizo el precio total del pedido
        $pedido->setPrecioTotal($precioActualizado);

        $entityManager->flush();

        
        return $this->redirectToRoute('ver_pedido');
    }
}
