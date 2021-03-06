<?php
// api/src/Entity/MediaObjectRepository.php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateMediaObjectAction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Repository\MediaObjectRepository;

/**
 * @ORM\Entity(repositoryClass=MediaObjectRepository::class)
 * @Vich\Uploadable
 */
#[ApiResource(
    collectionOperations: [
        "post" => [
            "controller" => CreateMediaObjectAction::class,
            "deserialize" => false,
            "validation_groups" => ["Default", "media_object_create"],
            "openapi_context"=> [
                "requestBody" => [
                    "content" => [
                        "multipart/form-data"=> [
                            "schema"=> [
                                "type" => "object",
                                "properties" => [
                                    "file" => [
                                        "type" => "string",
                                        "format" => "binary"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],

    itemOperations: ["get"],
    normalizationContext: [
    "groups" => ["media_object_read"]
    ],
)]
class MediaObject
{
    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"media_object_read"})
     */
    public $contentUrl;

    /**
     * @var File|null
     *
     * @Assert\NotNull(groups={"media_object_create"})
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="filePath")
     */
    public $file;

    /**
     * @var string|null
     *
     * @ORM\Column(nullable=true)
     */
    private $filePath;

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
    /**
     * @return int|null
     * @Groups({"media_object_read"})
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): self
    {
        $this->file = $file;
        $this->setUpdatedAt(new \DateTime());
        return $this;
    }
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;
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
}