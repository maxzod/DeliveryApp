<?php


namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class MessageRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->content = $data['content'];
        $this->type = $data['type'];
    }
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public string $content;
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public int $type;
}