<?php


namespace App\Dto;


class OrderResponse
{
    public ?int $id;
    public $price;
    public ?int $status;
    public ?string $note;
    public ?string $createdAt;
    /**
     * @var UserResponse $owner
     */
    public $owner;
    /**
     * @var UserResponse $driver
     */
    public $driver;
    /**
     * @var ProductResponse[]
     */
    public $products;
    /**
     * @var OrderBillResponse[]
     */
    public $orderBills;
    /**
     * @var PlaceDto $place
     */
    public $place;
    /**
     * @var PlaceDto $drop_place
     */
    public $drop_place;
    /**
     * @var CouponResponse $coupon
     */
    public $coupon;
    public ?int $conversation;
}