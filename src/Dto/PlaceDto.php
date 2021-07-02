<?php


namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class PlaceDto
{
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public string $name;
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public string $longitude;
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public string $latitude;
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public string $address;
}