<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Dto\UserResponse;
use App\Dto\ReviewResponse;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(collectionOperations={}, itemOperations={"get"},
 *     normalizationContext={"groups":{"user:read"}})
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email","phone", "stcpay"})
 */
#[ApiResource(
    collectionOperations: [],
    itemOperations: ["get",
        "reviews" => [
            "method" => "GET",
            "route" => "api.user.reviews",
            "output" => ReviewResponse::class
        ]],
    normalizationContext: [
        "groups" => ["user:read"]
    ]
)]
class User implements UserInterface
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_WAITING = 2;
    public const STATUS_REFUSED = 3;
    public const STATUS_BANNED = 4;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"user:read"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"user:read"})
     */
    private $stcpay;

    /**
     * @ORM\Column(type="integer", nullable=true, length=4)
     */
    private $code;

    /**
     * @ORM\Column(type="integer", length=1, options={"default": 2})
     * @Groups({"user:read"})
     */
    private $account_status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read"})
     */
    private $status_note;

    /**
     * @ORM\OneToMany(targetEntity=Complaints::class, mappedBy="owner")
     */
    private $complaints;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="owner")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="driver")
     */
    private $driverOrders;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read"})
     */
    private $longitude;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="reviewed", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $account_balance;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $total_delivery_fees;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile_token;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="owner", cascade={"persist"})
     */
    private $notifications;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\Choice({"male", "female"})
     *  @ORM\Column(type="string", length=255)
     */
    private $gender;
    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    public $image;

    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    public $form_img;
    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    public $license_img;
    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    public $front_img;
    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    public $back_img;
    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    public $id_card_img;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $id_number;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $permissions = [];


    public function __construct()
    {
        $this->complaints = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->driverOrders = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->notifications = new ArrayCollection();
    }

    public static function createClient(string $name, string $email, string $gender, MediaObject $image, string $longitude, string $latitude, string $phone, ?string $stcPay) : self
    {
        $user = new self();
        $user->setName($name);
        $user->setEmail($email);
        $user->setGender($gender);
        $user->setImage($image);
        $user->setAccountStatus(self::STATUS_ACTIVE);
        $user->setLongitude($longitude);
        $user->setLatitude($latitude);
        $user->setPhone($phone);
        $user->setStcpay($stcPay);
        $user->setRoles(["ROLE_CLIENT"]);
        return $user;
    }
    public static function createDriver(string $name, string $email, string $gender,
                                        MediaObject $image, string $longitude, string $latitude,
                                        string $phone, ?string $stcPay,
                                        MediaObject $form_img, MediaObject $front_img, MediaObject $back_img,
                                        MediaObject $license_img, MediaObject $id_card_img, string $id_number) : self
    {
        $user = new self();
        $user->setName($name);
        $user->setEmail($email);
        $user->setGender($gender);
        $user->setImage($image);
        $user->setAccountStatus(self::STATUS_WAITING);
        $user->setLongitude($longitude);
        $user->setLatitude($latitude);
        $user->setPhone($phone);
        $user->setStcpay($stcPay);
        $user->setRoles(["ROLE_DRIVER"]);
        $user->setFormImg($form_img);
        $user->setFrontImg($front_img);
        $user->setBackImg($back_img);
        $user->setLicenseImg($license_img);
        $user->setIdCardImg($id_card_img);
        $user->setIdNumber($id_number);
        return $user;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    #[Pure]
    public function getUserIdentifier(): string
    {
        return  (string)$this->getId();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStcpay(): ?string
    {
        return $this->stcpay;
    }

    public function setStcpay(string $stcpay): self
    {
        $this->stcpay = $stcpay;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAccountStatus(): ?int
    {
        return $this->account_status;
    }

    public function setAccountStatus(int $account_status): self
    {
        $this->account_status = $account_status;

        return $this;
    }

    public function getStatusNote(): ?string
    {
        return $this->status_note;
    }

    public function setStatusNote(?string $status_note): self
    {
        $this->status_note = $status_note;

        return $this;
    }

    /**
     * @return Collection|Complaints[]
     */
    public function getComplaints(): Collection
    {
        return $this->complaints;
    }

    public function addComplaint(Complaints $complaint): self
    {
        if (!$this->complaints->contains($complaint)) {
            $this->complaints[] = $complaint;
            $complaint->setOwner($this);
        }

        return $this;
    }

    public function removeComplaint(Complaints $complaint): self
    {
        if ($this->complaints->removeElement($complaint)) {
            // set the owning side to null (unless already changed)
            if ($complaint->getOwner() === $this) {
                $complaint->setOwner(null);
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
            $order->setOwner($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getOwner() === $this) {
                $order->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getDriverOrders(): Collection
    {
        return $this->driverOrders;
    }

    public function addDriverOrder(Order $driverOrder): self
    {
        if (!$this->driverOrders->contains($driverOrder)) {
            $this->driverOrders[] = $driverOrder;
            $driverOrder->setDriver($this);
        }

        return $this;
    }

    public function removeDriverOrder(Order $driverOrder): self
    {
        if ($this->driverOrders->removeElement($driverOrder)) {
            // set the owning side to null (unless already changed)
            if ($driverOrder->getDriver() === $this) {
                $driverOrder->setDriver(null);
            }
        }

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setReviewed($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getReviewed() === $this) {
                $review->setReviewed(null);
            }
        }

        return $this;
    }

    public function getAccountBalance(): ?string
    {
        return $this->account_balance;
    }

    public function setAccountBalance(?string $account_balance): self
    {
        $this->account_balance = $account_balance;

        return $this;
    }

    public function getTotalDeliveryFees(): ?string
    {
        return $this->total_delivery_fees;
    }

    public function setTotalDeliveryFees(?string $total_delivery_fees): self
    {
        $this->total_delivery_fees = $total_delivery_fees;

        return $this;
    }
    public function __toString()
    {
        if($this->getRoles()[0] == 'ROLE_DRIVER')
        {
            return $this->getName().' (سائق)';
        }
        if($this->getRoles()[0] == 'ROLE_CLIENT')
        {
            return $this->getName().' (عميل)';
        }
        return $this->getName();

    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMobileToken(): ?string
    {
        return $this->mobile_token;
    }

    public function setMobileToken(?string $mobile_token): self
    {
        $this->mobile_token = $mobile_token;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setOwner($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getOwner() === $this) {
                $notification->setOwner(null);
            }
        }

        return $this;
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

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getPermissions(): ?array
    {
        return $this->permissions;
    }

    public function setPermissions(?array $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function getAvgReviews()
    {
        $stars = [];
        foreach ($this->getReviews() as $review) {
            array_push($stars, $review->getStars());
        }
        if(count($stars) == 0){
            return 0;
        }
        return round(array_sum($stars) / count($this->getReviews()), 1);
    }

    public function getIdNumber(): ?string
    {
        return $this->id_number;
    }

    public function setIdNumber(?string $id_number): self
    {
        $this->id_number = $id_number;

        return $this;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getFormImg(): ?MediaObject
    {
        return $this->form_img;
    }

    public function setFormImg(?MediaObject $form_img): self
    {
        $this->form_img = $form_img;

        return $this;
    }

    public function getLicenseImg(): ?MediaObject
    {
        return $this->license_img;
    }

    public function setLicenseImg(?MediaObject $license_img): self
    {
        $this->license_img = $license_img;

        return $this;
    }

    public function getFrontImg(): ?MediaObject
    {
        return $this->front_img;
    }

    public function setFrontImg(?MediaObject $front_img): self
    {
        $this->front_img = $front_img;

        return $this;
    }

    public function getBackImg(): ?MediaObject
    {
        return $this->back_img;
    }

    public function setBackImg(?MediaObject $back_img): self
    {
        $this->back_img = $back_img;

        return $this;
    }

    public function getIdCardImg(): ?MediaObject
    {
        return $this->id_card_img;
    }

    public function setIdCardImg(?MediaObject $id_card_img): self
    {
        $this->id_card_img = $id_card_img;

        return $this;
    }
}
