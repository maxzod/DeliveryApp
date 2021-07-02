<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OfferRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Dto\OfferResponse;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 */
#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        "get" => [
            "security" => "object.getDriver() == user or object.getTheOrder().getOwner() == user"
        ],
        "accept" => [
            "route_name" => "client.offer.accept",
            "method" => "GET",
            "input" => false,
            "output" => false
        ]
    ]
)]
class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $driver;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getTheOrder(): ?Order
    {
        return $this->theOrder;
    }

    public function setTheOrder(?Order $theOrder): self
    {
        $this->theOrder = $theOrder;

        return $this;
    }
}
