<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request): Response
    {
        $reservation = $request->get('reservation');
        $nbNights = (strtotime($reservation['checkOut']) - strtotime($reservation['checkIn'])) / (60 * 60 * 24);
        $price = $nbNights * $reservation['pricePerNight'];

        $clientInfos = [];

        $form = $this->createFormBuilder($clientInfos)
                    ->add('firstname', TextType::class, [
                        'label' => 'Prénom',
                        'required' => true,
                    ])
                    ->add('lastname', TextType::class, [
                        'label' => 'Nom',
                        'required' => true,
                    ])
                    ->add('email', EmailType::class, [
                        'label' => 'Email',
                        'required' => true,
                    ])
                    ->add('phone', TelType::class, [
                        'label' => 'Téléphone',
                        'required' => true,
                    ])
                    ->add('address', TextType::class, [
                        'label' => 'Adresse',
                        'required' => true,
                    ])
                    ->add('city', TextType::class, [
                        'label' => 'Ville',
                        'required' => true,
                    ])
                    ->add('zipCode', NumberType::class, [
                        'label' => 'Code postal',
                        'required' => true,
                    ])
                    ->add('country', TextType::class, [
                        'label' => 'Pays',
                        'required' => true,
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Payer la réservation',
                    ])
                    ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientInfos = $form->getData();
        }

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'reservation' => $reservation,
            'nbNights' => $nbNights,
            'price' => $price,
            'clientInfosForm' => $form->createView(),
        ]);
    }
}
