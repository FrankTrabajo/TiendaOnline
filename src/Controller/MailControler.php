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

final class MailControler extends AbstractController{
    #[Route("/mail")]
    public function sendMail(EntityManagerInterface $entityManger, MailerInterface $mail, SessionInterface $session): Response{
        
        $usuario = $this->getUser();
        $user = $entityManger->getRepository(Usuario::class)->find($usuario);
        //QUEREMOSA algo de la clase pedido por eso es class Pedido :D
        $pedido = $entityManger->getRepository(Pedido::class)->findOneBy([
            
            'usuario'=>$usuario
        ]);
        $email= (new Email())
            ->from("tiendaOnlineNLF@gmail.com")
            ->to($user->getEmail())
            ->subject("Pedido realizado correctamente")
            ->html('
            <h1>Pedido realizado correctamente</h1>
            <p>Correo llegado</p>
            ');
        $mail->send($email);

        return new Response('Email enviado correctamente');
    }
}
