<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use App\Entity\CardFavoris;
use App\Entity\Category;
use App\Entity\CollectionCard;
use App\Entity\Connexion;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
       // return $this->redirect($adminUrlGenerator->setController(CardCrudController::class)->generateUrl());

    #[Route(path: '/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(EntityManagerInterface $em): Response
    {
        $collections = $em->getRepository(CollectionCard::class)->findAll();
        $cards = $em->getRepository(Card::class)->findAll();
        $connexions = $em->getRepository(Connexion::class)->findAll();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/my-dashboard.html.twig',[
            'collections' => count($collections),
            'cards' => count($cards),
            'connexions' => count($connexions),
            'users' => count($users)
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Collect7');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Cards', 'fas fa-list', Card::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Collections', 'fas fa-list', CollectionCard::class);
        yield MenuItem::linkToCrud('Category', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Connexion', 'fas fa-list', Connexion::class);
        yield MenuItem::linkToCrud('Card Favoris', 'fas fa-list', CardFavoris::class);
    }
}
