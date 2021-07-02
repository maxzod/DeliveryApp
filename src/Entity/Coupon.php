<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CouponRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CouponRepository::class)
 */
class Coupon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=4)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Range(min="1")
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private $expireAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFixedNumber;
    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="coupon")
     */
    private $orders;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expireAt;
    }

    public function setExpireAt(\DateTimeInterface $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getHasFixedNumber(): ?bool
    {
        return $this->hasFixedNumber;
    }

    public function setHasFixedNumber(bool $hasFixedNumber): self
    {
        $this->hasFixedNumber = $hasFixedNumber;

        return $this;
    }

    public function getFixedNumber(): ?int
    {
        return $this->fixedNumber;
    }

    public function setFixedNumber(?int $fixedNumber): self
    {
        $this->fixedNumber = $fixedNumber;

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
            $order->setCoupon($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCoupon() === $this) {
                $order->setCoupon(null);
            }
        }

        return $this;
    }
}
