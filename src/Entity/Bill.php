<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\BillRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\MediaObject;

/**
 *
 * @ORM\Entity(repositoryClass=BillRepository::class)
 */
#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        "DELETE" => [
            "route" => "bills.delete",
            "path" => "/bills/{id}",
            "method" => "DELETE"
        ]
    ]
)]
class Bill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    public $image;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderBills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theOrder;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(MediaObject $image): self
    {
        $this->image = $image;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}