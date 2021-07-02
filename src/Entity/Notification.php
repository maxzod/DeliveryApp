<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Dto\NotificationResponse;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        "get"
    ],
    itemOperations: [
        "get" => [
            "security" => "object.getOwner() == user"
        ]
    ],
    output: NotificationResponse::class
)]
class Notification
{
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
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notifications")
     */
    private $owner;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
}
