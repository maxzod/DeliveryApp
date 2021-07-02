<?php


namespace App\Dto;


use Symfony\Component\Serializer\Annotation\Groups;

class ReviewResponse
{
    /**
     * @Groups({"user:read"})
     */
    public int $id;
    /**
     * @Groups({"user:read"})
     */
    public UserResponse $reviewer;
    /**
     * @Groups({"user:read"})
     */
    public UserResponse $reviewed;
    /**
     * @Groups({"user:read"})
     */
    public int $stars;
    /**
     * @Groups({"user:read"})
     */
    public string $comment;
}