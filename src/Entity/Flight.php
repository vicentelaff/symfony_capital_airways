<?php

namespace App\Entity;

use App\Entity\City;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FlightRepository;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 */
class Flight
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $flightSchedule;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Enter a price")
     * @Assert\Range(
     *      min=40,
     *      max=9999,
     *      minMessage="Minimum 40€",
     *      maxMessage="Maximum 9999€"
     * )
     * 
     */
    private $flightPrice;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $discount;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="flights")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departure;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="flights")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotEqualTo(
     *          propertyPath="departure",
     *          message="The departure and arrival cities must be different."
     * )
     * 
     */
    private $arrival;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $flightNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Enter a number of available palces between 1 and 50")
     * @Assert\Range(
     *      min=1,
     *      max=50,
     *      minMessage="Minimum 1",
     *      maxMessage="Maximum 50"
     * )
     */
    private $places;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlightSchedule(): ?\DateTimeInterface
    {
        return $this->flightSchedule;
    }

    public function setFlightSchedule(\DateTimeInterface $flightSchedule): self
    {
        $this->flightSchedule = $flightSchedule;

        return $this;
    }

    public function getFlightPrice(): ?float
    {
        return $this->flightPrice;
    }

    public function setFlightPrice(float $flightPrice): self
    {
        $this->flightPrice = $flightPrice;

        return $this;
    }

    public function getDiscount(): ?bool
    {
        return $this->discount;
    }

    public function setDiscount(?bool $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDeparture(): ?City
    {
        return $this->departure;
    }

    public function setDeparture(?City $departure): self
    {
        $this->departure = $departure;

        return $this;
    }

    public function getArrival(): ?City
    {
        return $this->arrival;
    }

    public function setArrival(?City $arrival): self
    {
        $this->arrival = $arrival;

        return $this;
    }

    public function getFlightNumber(): ?string
    {
        return $this->flightNumber;
    }

    public function setFlightNumber(string $flightNumber): self
    {
        $this->flightNumber = $flightNumber;

        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(?int $places): self
    {
        $this->places = $places;

        return $this;
    }

}
