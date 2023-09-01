<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Card::class;
    }

    
     public function configureFields(string $pageName): iterable
    {
         return [
             TextField::new('name'),
             NumberField::new('price'),
             BooleanField::new('ifAvailable'),
             AssociationField::new('collection'),
             TextField::new('imageFile')->setFormType(VichImageType::class)->onlyWhenCreating(),
             ImageField::new('imageName')->setBasePath('/uploads/cards')->onlyOnIndex(),
         ];
     }
    
}
