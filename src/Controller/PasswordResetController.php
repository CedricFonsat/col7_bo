<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PasswordResetToken;
use App\Form\RequestPasswordResetType;
use App\Service\TokenGenerator;
use App\Service\EmailSender;

class PasswordResetController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em){}

    #[Route('/request-password-reset', name: 'request_password_reset' ,methods: 'POST')]
    public function requestPasswordReset(Request $request, TokenGenerator $tokenGenerator, EmailSender $emailSender): JsonResponse
    {
        $form = $this->createForm(RequestPasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->get('email')->getData();
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
                $this->addFlash('success', 'Un e-mail de réinitialisation de mot de passe a été envoyé à votre adresse e-mail.');
              //  return $this->redirectToRoute('login');
                return new JsonResponse(['message' => 'Réinitialisation de mot de passe réussie']);


            } else {
                return new JsonResponse(['message' => 'Erreur: Aucun utilisateur trouvé avec cette adresse e-mail.'], 400);
              //  $this->addFlash('error', 'Aucun utilisateur trouvé avec cette adresse e-mail.');
            }
        }

        return new JsonResponse(['message' => true]);


     /*   return $this->render('password_reset/request_password_reset.html.twig', [
            'form' => $form->createView(),
        ]);*/
    }

    #[Route('reset-password/{token}', name: 'reset_password')]
    public function resetPassword(Request $request, UserPasswordHasherInterface $hashPassword, $token): Response
    {
        $passwordResetToken = $this->em->getRepository(PasswordResetToken::class)->findOneBy(['token' => $token]);
        if (!$passwordResetToken || $passwordResetToken->getExpiresAt() < new \DateTime()) {
            return $this->render('password_reset/reset_password_error.html.twig');
        }

        $user = $passwordResetToken->getUser();

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($hashPassword->hashPassword($user, $form->get('password')->getData()));
            $this->em->remove($passwordResetToken);
            $this->em->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
            return $this->redirectToRoute('reset_password_success');
        }

        return $this->render('password_reset/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('reset-password-success', name: 'reset_password_success')]
    public function resetPasswordSuccess(): Response
    {
        return $this->render('password_reset/reset_password_success.html.twig');
    }

}
