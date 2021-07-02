<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Dto\ReviewRequest;
use App\Dto\ReviewResponse;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
#[ApiResource(
    collectionOperations:["get"],
    itemOperations:["get"],
    input:ReviewRequest::class,
    output:ReviewResponse::class
)]
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $reviewer;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reviewed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="integer")
     */
    private $stars;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReviewer(): ?User
    {
        return $this->reviewer;
    }

    public function setReviewer(?User $reviewer): self
    {
        $this->reviewer = $reviewer;

        return $this;
    }

    public function getReviewed(): ?User
    {
        return $this->reviewed;
    }

    public function setReviewed(?User $reviewed): self
    {
        $this->reviewed = $reviewed;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(int $stars): self
    {
        $this->stars = $stars;

        return $this;
    }
//    public function __toString()
//    {
//        return (string)$this->stars;
//    }

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
