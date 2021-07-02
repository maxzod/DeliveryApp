<?php


namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class OrderRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->note = $data['note'];
        $this->coupon = $data['coupon'];
        $this->place = new PlaceDto();
        $this->place->name = $data['place']['name'];
        $this->place->address = $data['place']['address'];
        $this->place->longitude = $data['place']['longitude'];
        $this->place->latitude = $data['place']['latitude'];
        $this->drop_place = new PlaceDto();
        $this->drop_place->name = $data['drop_place']['name'];
        $this->drop_place->address = $data['drop_place']['address'];
        $this->drop_place->longitude = $data['drop_place']['longitude'];
        $this->drop_place->latitude = $data['drop_place']['latitude'];
        foreach ($data['products'] as $product){
            $prd = new ProductRequest();
            $prd->name = $product['name'];
            $prd->image = $product['image'];
            $prd->quantity = $product['quantity'];
            array_push($this->products, $prd);
        }
    }
    public string $note;
    /**
     * @Assert\Valid()
     * @var PlaceDto $place
     */
    public $place;
    /**
     * @Assert\Valid()
     * @var PlaceDto $place
     */
    public $drop_place;
    /**
     * @var ProductRequest[] $products
     * @Assert\Valid()
     */
    public $products;

    public $coupon;
}