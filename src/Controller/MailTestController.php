<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailTestController extends AbstractController
{
    #[Route('/test-email', name: 'test_email')]
    public function sendTestEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('kpignolet18@gmail.com')
            ->to('kennypignolet123@outlook.com')
            ->subject('Test Email')
            ->text('Ceci est un test de Symfony Mailer.')
            ->html('<p>Test de Symfony Mailer</p>');

        $mailer->send($email);

        return $this->json(['message' => 'Email envoyé !']);
    }
}
