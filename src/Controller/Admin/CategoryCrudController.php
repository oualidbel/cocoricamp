<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof Category) return;

        foreach ($entityInstance->getLodgings() as $lodging) {
            $em->remove($lodging);
        }

        parent::deleteEntity($em, $entityInstance);
    }
}
