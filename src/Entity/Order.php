<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\DriverMakeOfferController;
use App\Controller\ClientTakeOrderController;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Dto\OrderRequest;
use App\Dto\OrderResponse;
use App\Dto\DriverOfferRequest;
use App\Dto\ReviewRequest;
use App\Dto\OrderBillRequest;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Dto\ReviewResponse;
use App\Dto\OfferResponse;
use App\Dto\OrderBillResponse;

/**
 *
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
#[ApiResource(
    collectionOperations: [
    "get" => [
        "method" => "GET",
        "route" => "orders.index",
        "output" => OrderResponse::class
    ],
    "add" => [
            "method" => "POST",
            "route" => "orders.store",
            "input" => OrderRequest::class,
            "output" => false
        ],
    "driver.orders" => [
            "route"=>"driver.orders",
            "security"=>"is_granted('ROLE_DRIVER') and user.getAccountStatus() == 1",
            "output"=>OrderResponse::class
        ],
    ],
    itemOperations: [
    "get"=> [
            "security"=> "is_granted('ROLE_DRIVER') or object.getOwner() == user",
            "output"=>OrderResponse::class
        ],
    "getOrderOffers"=>[
            "security" => "object.getOwner() == user",
            "route_name" => "client.order.offers",
            "output" => OfferResponse::class
        ],
    "makeOffer"=>[
            "route_name" => "driver.order.offer",
            "method" => "POST",
            "security" => "is_granted('ROLE_DRIVER')",
            "input" => DriverOfferRequest::class,
            "output" => false
        ],
    "addOrderBill"=>[
            "route_name"=>"client.order.new_bill",
            "method"=>"POST",
            "security"=>"is_granted('ROLE_DRIVER')",
            "input"=>OrderBillRequest::class,
            "output"=>false
        ],
    "OrderArrived"=>[
            "route_name"=>"driver.order.arrived",
            "method"=>"GET",
            "security"=>"is_granted('ROLE_DRIVER') and object.getDriver() == user"
        ],
    "takeOrder"=>[
            "method"=>"GET",
            "route_name"=>"client.order.take",
            "security"=>"object.getOwner() == user"
        ],
    "reviewOrder"=>[
            "route_name"=>"order.review",
            "method"=>"POST",
            "security"=>"object.getOwner() == user or object.getDriver() == user",
            "input"=>ReviewRequest::class,
            "output"=>ReviewResponse::class
        ],
    ],
    output: OrderResponse::class
)]
#[ApiFilter(SearchFilter::class,
    properties: [
        "status" => SearchFilter::STRATEGY_EXACT
])]
class Order
{
    public const STATUS_WAITING = 1;
    public const STATUS_PROCESSING = 2;
    public const STATUS_DONE = 3;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     */
    private $owner;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="driverOrders")
     */
    private $driver;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="theOrder", cascade={"persist", "remove"})
     */
    private $products;
    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="theOrder", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $offers;

    /**
     * @ORM\ManyToOne(targetEntity=Coupon::class, inversedBy="orders")
     */
    private $coupon;

    /**
     * @ORM\OneToOne(targetEntity=OrderPlace::class, inversedBy="theOrder", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\OneToOne(targetEntity=DropPlace::class, inversedBy="theOrder", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="drop_place_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $dropPlace;

    /**
     * @ORM\ManyToOne(targetEntity=Conversation::class, inversedBy="orders")
     */
    private $conversation;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="theOrder", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=Complaints::class, mappedBy="theOrder", orphanRemoval=true)
     */
    private $complaints;

    /**
     * @ORM\OneToMany(targetEntity=Bill::class, mappedBy="theOrder")
     */
    private $orderBills;



    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->offers = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->complaints = new ArrayCollection();
        $this->orderBills = new ArrayCollection();
    }

    /**
     * @param string|null $note
     * @param OrderPlace $orderPlace
     * @param DropPlace $dropPlace
     * @param Product[] $products
     */
    public static function create(?string $note, OrderPlace $orderPlace, DropPlace $dropPlace, array $products, Coupon $coupon = null): Order
    {
        $order = new self();
        $order->setNote($note);
        $order->setPlace($orderPlace);
        $order->setDropPlace($dropPlace);
        foreach ($products as $product){
            $order->addProduct($product);
        }
        if($coupon){
            $order->setCoupon($coupon);
        }
        $order->setStatus(Order::STATUS_WAITING);
        return $order;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

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

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): self
    {
        $this->driver = $driver;

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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setTheOrder($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getTheOrder() === $this) {
                $product->setTheOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setTheOrder($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getTheOrder() === $this) {
                $offer->setTheOrder(null);
            }
        }

        return $this;
    }

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(?Coupon $coupon): self
    {
        $this->coupon = $coupon;

        return $this;
    }

    public function getPlace(): ?OrderPlace
    {
        return $this->place;
    }

    public function setPlace(?OrderPlace $place): self
    {
        $this->place = $place;
        $place->setTheOrder($this);
        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDropPlace(): ?DropPlace
    {
        return $this->dropPlace;
    }

    public function setDropPlace(?DropPlace $dropPlace): self
    {
        $this->dropPlace = $dropPlace;

        $dropPlace->setTheOrder($this);

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

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
            $review->setTheOrder($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getTheOrder() === $this) {
                $review->setTheOrder(null);
            }
        }

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
            $complaint->setTheOrder($this);
        }

        return $this;
    }

    public function removeComplaint(Complaints $complaint): self
    {
        if ($this->complaints->removeElement($complaint)) {
            // set the owning side to null (unless already changed)
            if ($complaint->getTheOrder() === $this) {
                $complaint->setTheOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bill[]
     */
    public function getOrderBills(): Collection
    {
        return $this->orderBills;
    }

    public function addOrderBill(Bill $orderBill): self
    {
        if (!$this->orderBills->contains($orderBill)) {
            $this->orderBills[] = $orderBill;
            $orderBill->setTheOrder($this);
        }

        return $this;
    }

    public function removeOrderBill(Bill $orderBill): self
    {
        if ($this->orderBills->removeElement($orderBill)) {
            // set the owning side to null (unless already changed)
            if ($orderBill->getTheOrder() === $this) {
                $orderBill->setTheOrder(null);
            }
        }

        return $this;
    }
}