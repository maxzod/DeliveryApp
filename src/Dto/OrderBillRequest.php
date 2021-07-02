<?php


namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class OrderBillRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->price = $data['price'];
        $this->image_id = $data['image_id'];
    }

    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Range(min="1")
     */
    public float $price;
    /**
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Range(min="1")
     */
    public int $image_id;
}