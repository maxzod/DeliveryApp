<?php


namespace App\Dto;


class OrderResponse
{
    public int $id;
    public $price;
    public int $status;
    public string $note;
    /**
     * @var UserResponse $owner
     */
    public $owner;
    /**
     * @var UserResponse $driver
     */
    public $driver;
    public string $createdAt;
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
    public int $conversation;
}