<?php

namespace App\Controller\Admin;

use App\Entity\CardFavoris;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class CardFavorisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CardFavoris::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('user'),
            AssociationField::new('cards'),
        ];
    }

}
