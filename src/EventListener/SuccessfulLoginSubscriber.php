<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\ConnexionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use App\Entity\Connexion;
use Symfony\Component\HttpFoundation\RequestStack;

class SuccessfulLoginSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RequestStack $requestStack,
        private readonly ConnexionRepository $connexionRepository
    ){}
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();
        $request = $this->requestStack->getCurrentRequest();


        if (!$user instanceof User) {
            return;
        }

        $ifUser = $this->em->getRepository(Connexion::class)->findOneBy(['user' => $user->getId()]);

        if (!empty($ifUser)){
            $this->connexionRepository->remove($ifUser);
        }
        $connexion = new Connexion();
        $connexion->setUser($user);
        $connexion->setIpAddress($request->getClientIp());
        $this->em->persist($connexion);
        $this->em->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccess',
        ];
    }
}
