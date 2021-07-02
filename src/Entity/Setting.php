<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\SettingsResponse;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 */
#[ApiResource(
    collectionOperations: [
    "getSettings"=> [
        "route_name"=>"settings.get",
        "output" => SettingsResponse::class]
    ],
    itemOperations: []
)]
class Setting
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=3)
     */
    private $logo;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Range (min="1")
     */
    private $commission;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $terms_conditions;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getCommission(): ?int
    {
        return $this->commission;
    }

    public function setCommission(int $commission): self
    {
        $this->commission = $commission;

        return $this;
    }

    public function getTermsConditions(): ?string
    {
        return $this->terms_conditions;
    }

    public function setTermsConditions(string $terms_conditions): self
    {
        $this->terms_conditions = $terms_conditions;

        return $this;
    }
}
