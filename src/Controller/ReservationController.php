<?php

namespace App\Controller;

use DateTime;
use App\Entity\Lodging;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/réservation', name: 'app_reservation', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entitymanager): Response
    {
        if ($user = $this->getUser()) {
            $userFirstname = $user->getFirstName();
            $userLastname = $user->getLastName();
            $userEmail = $user->getUserIdentifier();
        } else {
            $userFirstname = '';
            $userLastname = '';
            $userEmail = '';
        }

        $reservationInfos = $request->get('reservationInfos');
        $nbNights = (strtotime($reservationInfos['checkOut']) - strtotime($reservationInfos['checkIn'])) / (60 * 60 * 24);
        $price = $nbNights * $reservationInfos['pricePerNight'];

        $reservation = new Reservation();
        $lodging = $entitymanager->getRepository(Lodging::class)->find($reservationInfos['lodgingId']);

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
                    ->add('email', RepeatedType::class, [
                        'type' => EmailType::class,
                        'invalid_message' => 'Les adresses emails doivent correspondre.',
                        'required' => true,
                        'first_options'  => ['label' => 'Email'],
                        'second_options' => ['label' => 'Confirmer votre email'],
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
            $reservationInfosEntity = array_merge($reservationInfos, $clientInfos, ['price' => $price, 'checkIn' => DateTime::createFromFormat('Y-m-d', $reservationInfos['checkIn']), 'checkOut' => DateTime::createFromFormat('Y-m-d', $reservationInfos['checkOut']), 'address' => $clientInfos['address'] . ',' . $clientInfos['city'] . ',' . $clientInfos['zipCode'] . ',' . $clientInfos['country'], 'numberPeople' => $reservationInfos['adults'] + $reservationInfos['children'], 'reservationDate' => DateTime::createFromFormat('Y-m-d', $reservationInfos['checkIn']), 'checkOut' => DateTime::createFromFormat('Y-m-d', date('Y-m-d')), 'lodging' => $lodging]);
            unset($reservationInfosEntity['lodgingId'], $reservationInfosEntity['adults'], $reservationInfosEntity['children'], $reservationInfosEntity['pricePerNight'], $reservationInfosEntity['lodgingName'], $reservationInfosEntity['city'], $reservationInfosEntity['zipCode'], $reservationInfosEntity['country']);

            $reservation->setLodging($reservationInfosEntity['lodging']);
            $reservation->setReservationCheckin($reservationInfosEntity['checkIn']);
            $reservation->setReservationCheckout($reservationInfosEntity['checkOut']);
            $reservation->setReservationPrice($reservationInfosEntity['price']);
            $reservation->setNumberPeople($reservationInfosEntity['numberPeople']);
            $reservation->setClientAddress($reservationInfosEntity['address']);
            $reservation->setClientEmail($reservationInfosEntity['email']);
            $reservation->setClientFirstname($reservationInfosEntity['firstname']);
            $reservation->setClientLastname($reservationInfosEntity['lastname']);
            $reservation->setClientPhone($reservationInfosEntity['phone']);
            $reservation->setReservationDate($reservationInfosEntity['reservationDate']);
            $reservation->setReservationStatus('Pending');

            $entitymanager->persist($reservation);
            $entitymanager->flush();
            return $this->redirectToRoute('app_reservation_checkout', ['reservationId' => $reservation->getId()]);
        }

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'reservationInfos' => $reservationInfos,
            'nbNights' => $nbNights,
            'price' => $price,
            'userFirstname' => $userFirstname,
            'userLastname' => $userLastname,
            'userEmail' => $userEmail,
            'clientInfosForm' => $form->createView(),
        ]);
    }
}
