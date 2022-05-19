<?php

namespace App\Controller\Admin;

use App\Entity\Lodging;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\LodgingCrudController;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator){

    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
        ->setController(LodgingCrudController::class)
        ->generateUrl();

        return $this->redirect($url);

        //return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cocoricamp');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Hébergements', 'fa-solid fa-campground');

        yield MenuItem::subMenu('Actions', 'fa-solid fa-bars')->setSubItems([
            MenuItem::linkToCrud('Voir les hébergements', 'fas fa-eye', Lodging::class),
            MenuItem::linkToCrud('Ajouter un hébergement', 'fas fa-plus', Lodging::class)->setAction(Crud::PAGE_NEW)
        ]);

        yield MenuItem::subMenu('Catégories', 'fa-solid fa-bars')->setSubItems([
            MenuItem::linkToCrud('Voir les catégories', 'fas fa-eye', Category::class),
            MenuItem::linkToCrud('Ajouter une catégorie', 'fas fa-plus', Category::class)->setAction(Crud::PAGE_NEW)
        ]);
    }
}
