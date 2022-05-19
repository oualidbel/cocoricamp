<?php

namespace App\Controller\Admin;

use App\Entity\Lodging;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LodgingCrudController extends AbstractCrudController
{
    public const LODGINGS_BASE_PATH = 'upload/images/lodgings';
    public const LODGINGS_UPLOAD_DIR = 'public/upload/images/lodgings';
    
    public static function getEntityFqcn(): string
    {
        return Lodging::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextareaField::new('content', 'Description'),
            IntegerField::new('host_capacity', 'Capacité d\'hébergement'),
            ImageField::new('picture', 'Photo')
                ->setBasePath(self::LODGINGS_BASE_PATH)
                ->setUploadDir(self::LODGINGS_UPLOAD_DIR)
                ->setSortable(false),
            AssociationField::new('category', 'Catégorie'),
            MoneyField::new('price', 'Prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
        ];
    }
}
