<?php

namespace App\Controller;

use App\Repository\LodgingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(Request $request, LodgingRepository $repository): Response
    {
        $checkAvailability = [];

        $form = $this->createFormBuilder($checkAvailability)
                    ->add('adults', NumberType::class, [
                        'attr' => [
                            'min' => 0,
                            'max' => 8,
                            'value' => 0,
                            'id' => 'adults-input',
                            'disabled' => 'disabled'
                        ],
                    ])
                    ->add('children', NumberType::class, [
                        'attr' => [
                            'min' => 0,
                            'max' => 8,
                            'value' => 0,
                            'id' => 'children-input',
                            'disabled' => 'disabled'
                        ],
                    ])
                    ->add('lodging', ChoiceType::class, [
                        'choices' => [
                            'Type de logement ?' => null,
                            'Tipis' => 2,
                            'Cabanes' => 3,
                            'Tentes' => 4,
                        ],
                        'attr' => [
                            'placeholder' => 'Logement',
                        ],
                    ])
                    ->add('location', ChoiceType::class, [
                        'choices' => [
                            'Où ?' => null,
                            'Paris' => 1,
                            'Marseille' => 2,
                            'Lille' => 3,
                            'Nantes' => 4,
                            'Bordeaux' => 5,
                            'Lyon' => 6,
                            'Saint-Étienne' => 7,
                            'Nice' => 8,
                            'Tahiti Teahupo\'o' => 9,
                        ],
                        'attr' => [
                            'placeholder' => 'Logement',
                        ],
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Vérifier la disponibilité',
                        'attr' => [
                            'class' => 'submit',
                        ],
                    ])
                    ->getForm();

        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with ... keys
            $data = $form->getData();
            return $this->redirectToRoute('app_lodging_show', [
                'data' => $data,
            ]);
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'formCheckAvailibility' => $form->createView(),
        ]);
    }
}
