<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\DropPlaceRepository;
/**
 * @ORM\Entity(repositoryClass=DropPlaceRepository::class)
 */
class DropPlace extends Place
{
    /**
     * @ORM\OneToOne (targetEntity=Order::class, mappedBy="dropPlace", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $theOrder;

    public static function create(string $name, string $longitude, string $latitude, string $address): DropPlace
    {
        $place = new self();
        $place->setName($name);
        $place->setLongtude($longitude);
        $place->setLatitude($latitude);
        $place->setAddress($address);
        return $place;
    }
    public function getTheOrder(): Order
    {
        return $this->theOrder;
    }

    public function setTheOrder(Order $theOrder): self
    {
        $this->theOrder = $theOrder;

        return $this;
    }
}