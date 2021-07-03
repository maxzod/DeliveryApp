<?php


namespace App\Dto;


class ProductResponse
{
    public ?int $id;
    public ?string $name;
    public ?int $quantity;
    public ?ImageResponse $image;
}