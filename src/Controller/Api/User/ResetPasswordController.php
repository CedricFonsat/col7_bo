<?php

namespace App\Controller\Api\User;

use App\Entity\PasswordResetToken;
use App\Entity\User;
use App\Service\EmailSender;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function __invoke(Request $request, TokenGenerator $tokenGenerator, EmailSender $emailSender): JsonResponse
    {
        $dataUser = json_decode($request->getContent(), true);

        $email = $dataUser['email'];
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            $token = $tokenGenerator->generateToken();
            $expiresAt = new \DateTimeImmutable('+1 hour');

            $passwordResetToken = new PasswordResetToken();
            $passwordResetToken->setUser($user);
            $passwordResetToken->setToken($token);
            $passwordResetToken->setExpiresAt($expiresAt);
            $this->em->persist($passwordResetToken);
            $this->em->flush();

            $emailSender->sendPasswordResetEmail($user, $token);
            return new JsonResponse(['message' => 'Un email vous a été envoyer pour réinitialiser votre mot de passe']);

        } else {
            return new JsonResponse(['message' => 'Erreur: Aucun utilisateur trouvé avec cette adresse e-mail.'], 400);
        }
    }
}