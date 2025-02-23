<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    #[Route('/delete/{id}', name: 'delete_producto')]
    public function delete($id, EntityManagerInterface $entityManager): Response{
        //$this->denyAccessUnlessGranted('ROLE_USER');
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('index_producto');
        }
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
    #[Route('/create', name: 'create_producto')]
    public function create(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger): Response {
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('index_producto');
        }
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('foto')->getData();
            if($file){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename. '-'.uniqid().'.'.$file->guessExtension();
                try{
                    $file->move($this->getParameter('files_directory'), $newFilename);
                } catch (FileException $e){
                    throw new Exception('Hubo un error con la subida del archivo');
                }
            }
            $producto->setFoto($newFilename);
            $entityManager->persist($producto);
            $entityManager->flush();
            return $this->redirectToRoute('index_producto');
        }

        return $this->render('producto/create.html.twig', [
            'form' => $form->createView()
        ]);
    }



    //Update
    #[Route('/update/{id}', name: 'update_producto')]
    public function update($id, EntityManagerInterface $entityManager, Request $request): Response{
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('index_producto');
        }
        $producto = $entityManager->getRepository(Producto::class)->find($id);
        $form = $this->createForm(ProductoType::class, $producto);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();

            return $this->redirectToRoute('index_producto');
        }

        return $this->render('producto/update.html.twig', [
            'form' => $form->createView(),
            'producto' => $producto
        ]);

    }

    
}
