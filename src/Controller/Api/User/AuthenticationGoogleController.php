<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationGoogleController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly JWTTokenManagerInterface $jwtManager
    ){}

    public function __invoke(Request $request, UserRepository $userRepository): JsonResponse
    {
        $userData = json_decode($request->getContent(), true);

        $ifUser = $userRepository->findOneBy([ 'googleId' => $userData['googleId']]);
        $ifUserByMail = $userRepository->findOneBy(['email' => $userData['email']]);

      // $tesr = array_keys_exists(['nickname','email','googleId','googlePicture'], $userData);

       dd($userData['nickname'], $userData['email'],$userData['googleId'],$userData['googlePicture']);

        if ($ifUser){
            $token = $this->jwtManager->create($ifUser);
            return new JsonResponse(['token' => $token]);
        }

        if ($ifUserByMail) {
            return new JsonResponse(['error' => 'Vous avez déjà un compte associé à cet e-mail']);
        }

        if (array_keys_exists(['nickname','email','googleId','googlePicture'], $userData)){

            $user = new User();
            $user->setNickname($userData['nickname']);
            $user->setEmail($userData['email']);
            $user->setGoogleId($userData['googleId']);
            $user->setGooglePicture($userData['googlePicture']);

            $this->em->persist($user);
            $this->em->flush();

            $token = $this->jwtManager->create($user);
            return new JsonResponse(['token' => $token]);
        }

        return new JsonResponse(['error' => "Une erreur c'est produite lors de l'authentification"]);
    }
}