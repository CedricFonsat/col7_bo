<?php

// src/Service/EmailSender.php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class EmailSender
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly RouterInterface $router,
        private readonly Environment $twig)
    {}

    public function sendPasswordResetEmail($user, $token): void
    {
        $resetPasswordLink = $this->generateResetPasswordLink($token);

        $email = (new Email())
            ->from('fonsat.ri7@gmail.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de mot de passe')
            ->html($this->twig->render('emails/password_reset.html.twig', [
                'resetPasswordLink' => $resetPasswordLink,
            ]));

        $this->mailer->send($email);
    }

    private function generateResetPasswordLink($token): string
    {
        // Générez le lien avec le token
         return $this->router->generate('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
