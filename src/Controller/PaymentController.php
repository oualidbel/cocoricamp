<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Lodging;
use App\Entity\Reservation;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{

    #[Route('/payment', name: 'payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }


    #[Route('/réservation/paiement/{reservationId}', name: 'app_reservation_checkout', methods: ['GET', 'POST'])]
    public function checkout(Request $request, EntityManagerInterface $entitymanager, $stripeSK, int $reservationId): Response
    {
        $reservationId = $request->get('reservationId');
        $reservation = $entitymanager->getRepository(Reservation::class)->find($reservationId);
        Stripe::setApiKey($stripeSK);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                            'name' => $reservation->getLodging()->getTitle(),
                        ],
                        'unit_amount'  => $reservation->getReservationPrice() * 100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', ['reservationId' => $reservationId], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', ['reservationId' => $reservationId], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
    }


    #[Route('/réservation/succès', name: 'success_url')]
    public function successUrl(Request $request, EntityManagerInterface $entitymanager): Response
    {
        $reservationId = $request->get('reservationId');
        $reservation = $entitymanager->getRepository(Reservation::class)->find($reservationId);
        $reservation->setReservationStatus('paid');

        return $this->render('payment/success.html.twig', []);
    }


    #[Route('/réservation/annulation', name: 'cancel_url')]
    public function cancelUrl(Request $request, EntityManagerInterface $entitymanager): Response
    {
        $reservationId = $request->get('reservationId');
        $reservation = $entitymanager->getRepository(Reservation::class)->find($reservationId);
        $reservation->setReservationStatus('cancelled');
        return $this->render('payment/cancel.html.twig', []);
    }
}









