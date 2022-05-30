<?php

namespace App\Controller;

use App\Repository\LodgingRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientReservationsController extends AbstractController
{
    #[Route('/mes-réservations', name: 'app_client_reservations')]
    public function index(ReservationRepository $reservationRepository, LodgingRepository $lodgingRepository): Response
    {
        $user = $this->getUser();
        $reservationsImages = [];
        $reservationsDates = [];
        $reservations = $reservationRepository->findReservationsByClientEmail($user->getUserIdentifier());
        foreach ($reservations as $reservation) {
            array_push($reservationsImages, $lodgingRepository->findOneBy(['id' => $reservation->getLodging()])->getPicture());
            // format reservation date
            array_push($reservationsDates, $reservation->getReservationDate()->format('d/m/Y'));
        }

        return $this->render('client_reservations/index.html.twig', [
            'controller_name' => 'ClientReservationsController',
            'reservations' => $reservations,
            'reservationsImages' => $reservationsImages,
            'reservationsDates' => $reservationsDates,
            'index' => 0,
        ]);
    }
}
