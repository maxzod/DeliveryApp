<?php


namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class DriverOfferRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->price = $data['price'];
    }
     /**
      * @Assert\NotBlank(allowNull=false)
      * @Assert\Range(min="5")
      */
     public int $price;
}