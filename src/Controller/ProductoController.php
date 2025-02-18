<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductoController extends AbstractController
{
    //Select
    #[Route('/', name: 'index_producto', methods: ['get'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $productos = $entityManager->getRepository(Producto::class)->findAll();


        return $this->render('producto/index.html.twig', [
            'productos' => $productos,
        ]);
    }

    //Select / id
    #[Route('/show/{id}', name: 'show_producto', methods: ['get'])]
    public function show($id,EntityManagerInterface $entityManager): Response{

        $producto = $entityManager->find(Producto::class, $id);

        return $this->render('producto/show.html.twig', [
            'producto' => $producto
        ]);
    }

    //Delete / id
    #[Route('/delete/{id}', name: 'delete_producto', methods:['delete'])]
    public function delete($id, EntityManagerInterface $entityManager): Response{

        $producto = $entityManager->find(Producto::class, $id);

        if(!$producto){
            return $this->render('error404.html.twig', [
                'error' => 'Producto a eliminar no encontrdo'
            ]);
        }

        $entityManager->remove($producto);
        $entityManager->flush();

        return $this->redirectToRoute('index_producto');

    }

    //Create
    #[Route('/create', name: 'create_producto', methods: ['post'])]
    public function create(EntityManagerInterface $entityManager, Request $request): Response{

        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($producto);
            $entityManager->flush();

            return $this->redirectToRoute('index_producto');
        }

        return $this->render('producto/create.html.twig', [
            'form' => $form->createView()
        ]);

    }


    //Update
}
