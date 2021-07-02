<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
#[ApiResource(
    collectionOperations: ["get"],
    itemOperations: ["get"]
)]
class Category
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
     * @Assert\Length(min=2)
     */
    private $name_ar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=2)
     */
    private $name_en;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=2)
     */
    private $cat_type;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Range(min="1")
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=3)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;




    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameAr(): ?string
    {
        return $this->name_ar;
    }

    public function setNameAr(string $name_ar): self
    {
        $this->name_ar = $name_ar;

        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->name_en;
    }

    public function setNameEn(string $name_en): self
    {
        $this->name_en = $name_en;

        return $this;
    }

    public function getCatType(): ?string
    {
        return $this->cat_type;
    }

    public function setCatType(string $cat_type): self
    {
        $this->cat_type = $cat_type;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    public function __toString()
    {
        return $this->name_ar;
    }
    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }
}