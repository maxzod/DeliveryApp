<?php


namespace App\Dto;


class ComplaintsResponse
{
    public int $id;
    public string $title;
    public string $message;
    public UserResponse $owner;
    public int $order;
}