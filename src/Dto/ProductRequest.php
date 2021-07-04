<?php


namespace App\Dto;


use Symfony\Component\Validator\Constraints as Assert;

class ProductRequest
{
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=3)
     */
    public string $name;
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Range(min="1")
     */
    public int $quantity;
    /**
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Range(min="1")
     */
    public ?int $image;
}