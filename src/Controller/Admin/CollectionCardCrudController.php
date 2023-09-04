<?php

namespace App\Controller\Admin;

use App\Entity\CollectionCard;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CollectionCardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CollectionCard::class;
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('author'),
            TextEditorField::new('description'),
            AssociationField::new('category'),
            AssociationField::new('cards'),
            TextField::new('imageFile')->setFormType(VichImageType::class)->onlyWhenCreating(),
            ImageField::new('imageName')->setBasePath('/uploads/collections')->onlyOnIndex(),
        ];
    }

}
