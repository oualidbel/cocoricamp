<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $client_firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $client_lastname;

    #[ORM\Column(type: 'string', length: 255)]
    private $client_email;

    #[ORM\Column(type: 'string', length: 255)]
    private $client_phone;

    #[ORM\Column(type: 'string', length: 255)]
    private $client_address;

    #[ORM\Column(type: 'datetime')]
    private $reservation_checkin;

    #[ORM\Column(type: 'datetime')]
    private $reservation_checkout;

    #[ORM\Column(type: 'integer')]
    private $reservation_price;

    #[ORM\ManyToOne(targetEntity: Lodging::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $lodging;

    #[ORM\Column(type: 'integer')]
    private $number_people;

    #[ORM\Column(type: 'datetime')]
    private $reservation_date;

    #[ORM\Column(type: 'string', length: 255)]
    private $reservation_status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientFirstname(): ?string
    {
        return $this->client_firstname;
    }

    public function setClientFirstname(string $client_firstname): self
    {
        $this->client_firstname = $client_firstname;

        return $this;
    }

    public function getClientLastname(): ?string
    {
        return $this->client_lastname;
    }

    public function setClientLastname(string $client_lastname): self
    {
        $this->client_lastname = $client_lastname;

        return $this;
    }

    public function getClientEmail(): ?string
    {
        return $this->client_email;
    }

    public function setClientEmail(string $client_email): self
    {
        $this->client_email = $client_email;

        return $this;
    }

    public function getClientPhone(): ?string
    {
        return $this->client_phone;
    }

    public function setClientPhone(string $client_phone): self
    {
        $this->client_phone = $client_phone;

        return $this;
    }

    public function getClientAddress(): ?string
    {
        return $this->client_address;
    }

    public function setClientAddress(string $client_address): self
    {
        $this->client_address = $client_address;

        return $this;
    }

    public function getReservationCheckin(): ?\DateTimeInterface
    {
        return $this->reservation_checkin;
    }

    public function setReservationCheckin(\DateTimeInterface $reservation_checkin): self
    {
        $this->reservation_checkin = $reservation_checkin;

        return $this;
    }

    public function getReservationCheckout(): ?\DateTimeInterface
    {
        return $this->reservation_checkout;
    }

    public function setReservationCheckout(\DateTimeInterface $reservation_checkout): self
    {
        $this->reservation_checkout = $reservation_checkout;

        return $this;
    }

    public function getReservationPrice(): ?int
    {
        return $this->reservation_price;
    }

    public function setReservationPrice(int $reservation_price): self
    {
        $this->reservation_price = $reservation_price;

        return $this;
    }

    public function getLodging(): ?Lodging
    {
        return $this->lodging;
    }

    public function setLodging(Lodging $lodging): self
    {
        $this->lodging = $lodging;

        return $this;
    }

    public function getNumberPeople(): ?int
    {
        return $this->number_people;
    }

    public function setNumberPeople(int $number_people): self
    {
        $this->number_people = $number_people;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservation_date;
    }

    public function setReservationDate(\DateTimeInterface $reservation_date): self
    {
        $this->reservation_date = $reservation_date;

        return $this;
    }

    public function getReservationStatus(): ?string
    {
        return $this->reservation_status;
    }

    public function setReservationStatus(string $reservation_status): self
    {
        $this->reservation_status = $reservation_status;

        return $this;
    }
}
