<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use App\Form\ImageType;
use App\Repository\UserRepository;
use App\Utils\UploadedBase64File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\FileAbstraction\ReplacingFile;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\File\File;

class UsersUploadImageController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @throws FileNotFoundException
     */
    public function __invoke(Request $request, UserRepository $userRepository): Response
    {
        $requestData = json_decode($request->getContent(), true);

        $user = $userRepository->find($requestData['userId']); // Remplacez 6 par l'ID de l'utilisateur que vous souhaitez associer à l'image

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non trouvé.'], 404);
        }

        if ($user->getImageName()){
            $imagePathDelete = $this->getParameter('upload_directory') . '/users/' . $user->getImageName();
            unlink($imagePathDelete);
        }

        $base64Data = $requestData['base64'];
        $imageContent = base64_decode($base64Data);

        $imageName = $requestData['name'];

        $imagePath = $this->getParameter('upload_directory') . '/users/' . $imageName;

        // Enregistrez l'image sur le disque
        file_put_contents($imagePath, $imageContent);

        // Mettez à jour l'utilisateur avec le nom de l'image
        $user->setImageName($imageName);


        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(['message' => 'Image téléchargée avec succès.']);
    }
}
