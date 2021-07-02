<?php


namespace App\Dto;


class NotificationResponse
{
    public int $id;
    public string $title;
    public string $content;
    public UserResponse $owner;
}