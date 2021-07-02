<?php


namespace App\Dto;


class MessageResponse
{
    public int $id;
    public string $content;
    public int $type;
    public int $sender_id;
    public ?string $createdAt;
}