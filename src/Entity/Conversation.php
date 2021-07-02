<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Dto\MessageResponse;
use App\Dto\MessageRequest;

/**
 *
 * @ORM\Entity(repositoryClass=ConversationRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        "get" => [
            "security" => "is_granted('ROLE_CLIENT') or is_granted('ROLE_DRIVER')"
        ]
    ],
    itemOperations: [
        "get" => [
            "security" => "object.getDriver() == user or object.getClient() == user"
        ],
        "getConversations" => [
            "route_name" => "user.conversation.messages",
            "security" => "is_granted('ROLE_CLIENT') or is_granted('ROLE_DRIVER')",
            "output" => MessageResponse::class,
            "openapi_context" => [
                "parameters" => [
                    [
                        "name" => "page",
                        "in" => "query",
                        "description" => "page number",
                        "schema" => [
                              "type" => "string"
                        ]
                    ]
                ]
            ]
        ],
        "postConversationMessages" => [
            "route_name" => "user.conversation.messages.add",
            "method"=> "POST",
            "security"=> "object.getDriver() == user or object.getClient() == user",
            "input"=> MessageRequest::class,
            "output"=> false
        ]
    ]
)]
class Conversation
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
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $driver;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="conversation", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="conversation")
     */
    private $orders;


    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

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

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setConversation($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getConversation() === $this) {
                $order->setConversation(null);
            }
        }

        return $this;
    }
}
