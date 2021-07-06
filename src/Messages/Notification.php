<?php


namespace App\Messages;


use App\Entity\User;

class Notification
{
    private $title;
    private $content;
    private $user_id;
    private $inputs;

    public function __construct(string $title, string $content, int $user_id, string $inputs)
    {
        $this->title = $title;
        $this->content = $content;
        $this->user_id = $user_id;
        $this->inputs = $inputs;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getInputs(): string
    {
        return $this->inputs;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }


}