<?php

namespace App\Controller\Admin;

use App\Entity\Lodging;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LodgingCrudController extends AbstractCrudController
{
    public const ACTION_DUPLICATE = 'duplicate';
    public const LODGINGS_BASE_PATH = 'upload/images/lodgings';
    public const LODGINGS_UPLOAD_DIR = 'public/upload/images/lodgings';
    
    public static function getEntityFqcn(): string
    {
        return Lodging::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new(self::ACTION_DUPLICATE)->linkToCrudAction('duplicateLodging');
        
        return $actions
            ->add(Crud::PAGE_EDIT, $duplicate);
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

    public function duplicateLodging(
        AdminContext $context, 
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $em): Response
    {
        /** @var Product $product */
        $product = $context->getEntity()->getInstance();

        $duplicatedProduct = clone $product;

        parent::persistEntity($em, $duplicatedProduct);

        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicatedProduct->getId())
            ->generateUrl();

            return $this->redirect($url);
    }
}