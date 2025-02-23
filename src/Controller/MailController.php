<?php

namespace App\Controller;

use App\Entity\Pedido;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class MailController extends AbstractController
{
    #[Route("/mail", name: 'send_email')]
    public function sendMail(EntityManagerInterface $entityManger, MailerInterface $mailer, SessionInterface $session): Response
    {
        try {
            // Obtener el usuario actual
            $usuario = $this->getUser();
            if (!$usuario) {
                return new Response('Usuario no autenticado');
            }
            
            // Obtener el usuario completo de la base de datos
            $user = $entityManger->getRepository(Usuario::class)->find($usuario);
            if (!$user) {
                return new Response('No se encontró el usuario');
            }
            
            // Obtener el pedido asociado al usuario
            $pedido = $entityManger->getRepository(Pedido::class)->findOneBy([
                'usuario' => $usuario
            ]);
            if (!$pedido) {
                return new Response('No se encontró el pedido');
            }

            // Crear el correo
            $email = (new Email())
                ->from("frangargo.trabajo@gmail.com")
                ->to($user->getEmail())
                ->subject("Pedido realizado correctamente")
                ->html('
                    <h1>Pedido realizado correctamente</h1>
                    <p>Correo llegado</p>
                ');

            // Enviar el correo
            $mailer->send($email);

            return new Response('Email enviado correctamente');
        } catch (\Throwable $ex) {
            return new Response('Error: ' . $ex->getMessage() . ' Ha ocurrido un error');
        }
    }
}

