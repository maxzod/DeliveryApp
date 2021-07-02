<?php


namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ComplaintsRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->title = $data['title'];
        $this->message = $data['message'];
        $this->order = $data['order'];
    }
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=5)
     */
    public string $title;
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=5)
     */
    public string $message;
    /**
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Range(min="1")
     */
    public int $order;
}