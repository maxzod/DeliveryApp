<?php


namespace App\Dto;


class OfferResponse
{
    public int $id;
    public string $price;
    public UserResponse $driver;
}