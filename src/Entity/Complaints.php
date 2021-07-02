<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ComplaintsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Dto\ComplaintsRequest;
use App\Dto\ComplaintsResponse;

/**
 *
 * @ORM\Entity(repositoryClass=ComplaintsRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        "get" => [
            "output" => ComplaintsResponse::class
        ],
        "post" => [
            "input" => ComplaintsRequest::class,
            "output" => ComplaintsResponse::class
        ]
    ],
    itemOperations: [
        "get" => [
            "security" => "object.getOwner() == user",
            "output" => ComplaintsResponse::class
        ]
    ]
)]
class Complaints
{
    public const STATUS_OPEN = 0;
    public const STATUS_CLOSED = 1;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="complaints")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="complaints")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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
