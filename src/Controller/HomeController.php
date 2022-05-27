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
                    ->add('check_in', DateType::class, [
                        'widget' => 'single_text',
                        'required' => false,
                        'attr' => [
                            'placeholder' => 'Date d\'arrivée',
                            'min' => date('Y-m-d'),
                            'value' => date('Y-m-d'),
                        ],
                    ])
                    ->add('check_out', DateType::class, [
                        'widget' => 'single_text',
                        'required' => false,
                        'attr' => [
                            'placeholder' => 'Date de départ',
                            'min' => date('Y-m-d', strtotime('+1 day')),
                            'value' => date('Y-m-d', strtotime('+1 day')),
                        ],
                    ])
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
