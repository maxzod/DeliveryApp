<?php


namespace App\Dto;


use App\Entity\MediaObject;
use Symfony\Component\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as JMS;

class UserResponse
{
    /**
     * @Groups({"user:read"})
     */
    public int $id;
    /**
     * @Groups({"user:read"})
     */
    public string $name;
    /**
     * @Groups({"user:read"})
     */
    public string $email;
    /**
     * @Groups({"user:read"})
     */
    public int $role;
    /**
     * @Groups({"user:read"})
     */
    public int $account_status;
    /**
     * @Groups({"user:read"})
     */
    public ?string $status_note;
    /**
     * @Groups({"user:read"})
     */
    public string $phone;
    /**
     * @Groups({"user:read"})
     */
    public ?string $stcPay;

    public string $gender;
    /**
     * @Groups({"user:read"})
     */
    public string $latitude;

    /**
     * @Groups({"user:read"})
     */
    public string $longitude;
    /**
     * @var MediaObject $image
     * @JMS\Type("App\Entity\MediaObject")
     * @JMS\SerializedName("image")
     */
    public $image;

    public $stars;
}