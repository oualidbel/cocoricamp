<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManager;
use App\Repository\LodgingRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LodgingController extends AbstractController
{
    #[Route('/hébergements', name: 'app_lodging')]
    public function index(): Response
    {
        return $this->render('lodging/index.html.twig', [
            'controller_name' => 'LodgingController',
        ]);
    }

    #[Route('/hébergements/disponible/', name: 'app_lodging_show')]
    public function show(Request $request, LodgingRepository $repository): Response
    {

        if (!isset($category)) {
            $category = 'all';
        }

        if ($request->get('data') == null) {
            $lodgings = $repository->findAll();
        } else {
            $data = $request->get('data');
            $lodgings = $repository->findByFilter($data);
        }

        $checkAvailability = [];

        $form = $this->createFormBuilder($checkAvailability)
                    ->add('adults', NumberType::class, [
                        'attr' => [
                            'min' => 0,
                            'max' => 8,
                            'value' => 0,
                            'id' => 'adults-input',
                        ],
                    ])
                    ->add('children', NumberType::class, [
                        'attr' => [
                            'min' => 0,
                            'max' => 8,
                            'value' => 0,
                            'id' => 'children-input',
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

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with ... keys
            $data = $form->getData();
            $lodgings = $repository->findByFilter($data);
            $category = $data['lodging'];
        }
        
        return $this->render('lodging/show.html.twig', [
            'controller_name' => 'LodgingController',
            'lodgings' => $lodgings,
            'category' => $category,
            'formCheckAvailibility' => $form->createView(),
        ]);
    }

    #[Route('/hébergements/disponible/{id}', name: 'app_lodging_show_id', methods: ['GET', 'POST'])]
    public function showId(LodgingRepository $repository, ReservationRepository $reservationRepository, Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $lodging = $repository->find($id);

        $reservationInfos = [];

        $form = $this->createFormBuilder($reservationInfos)
                    ->add('checkin', DateType::class, [
                        'widget' => 'single_text',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Date d\'arrivée',
                            'min' => date('Y-m-d'),
                            'value' => date('Y-m-d'),
                        ],
                    ])
                    ->add('checkout', DateType::class, [
                        'widget' => 'single_text',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Date de départ',
                            'min' => date('Y-m-d', strtotime('+1 day')),
                            'value' => date('Y-m-d', strtotime('+1 day')),
                        ],
                    ])
                    ->add('adults', NumberType::class, [
                        'required' => true,
                        'attr' => [
                            'min' => 1,
                            'max' => $lodging->getHostCapacity(),
                            'value' => 0,
                            'id' => 'adults-input',
                        ],
                    ])
                    ->add('children', NumberType::class, [
                        'attr' => [
                            'min' => 0,
                            'max' => $lodging->getHostCapacity(),
                            'value' => 0,
                            'id' => 'children-input',
                        ],
                    ])
                    ->getForm();

        // check values of chekout and checkin if on change, checkin is in between checkin and checkout of all reservations


        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid() && $form->getData()['adults'] + $form->getData()['children'] > 0) {
            // data is an array with ... keys
            $data = $form->getData();
            // if checkin is in between checkin and checkout of all reservations
            $reservations = $reservationRepository->findReservationsByLodgingId($id);
            if ($reservations) {
                foreach ($reservations as $reservation) {
                    if ($reservation->getReservationCheckin() <= $data['checkin'] && $reservation->getReservationCheckout() >= $data['checkout']) {
                        $this->addFlash('danger', 'Désolé, il y a déjà une réservation entre ces dates.');
                        return $this->redirectToRoute('app_lodging_show_id', ['id' => $id]);
                    }
                }
            }
            $lodgingName = $lodging->getTitle();
            $checkIn = $data['checkin']->format('Y-m-d');
            $checkOut = $data['checkout']->format('Y-m-d');
            $pricePerNight = $lodging->getPrice();
            $reservationInfos = array_merge($data, ['checkIn' => $checkIn, 'checkOut' => $checkOut, 'pricePerNight' => $pricePerNight, 'lodgingId' => $id, 'lodgingName' => $lodgingName]);
            return $this->redirectToRoute('app_reservation', [
                'reservationInfos' => $reservationInfos,
            ]);
        }

        return $this->render('lodging/showId.html.twig', [
            'controller_name' => 'LodgingController',
            'ReservationForm' => $form->createView(),
            'lodging' => $lodging,
        ]);
    }
}
