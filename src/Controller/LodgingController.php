<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LodgingController extends AbstractController
{
    #[Route('/lodging', name: 'app_lodging')]
    public function index(): Response
    {
        return $this->render('lodging/index.html.twig', [
            'controller_name' => 'LodgingController',
        ]);
    }
}
