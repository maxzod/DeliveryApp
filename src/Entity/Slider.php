<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SliderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SliderRepository::class)
 */
#[ApiResource(
    collectionOperations: ["get"],
    itemOperations: ["get"]
)]
class Slider
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
     * @Assert\Length(min=3)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Range(min="1")
     */
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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
}
