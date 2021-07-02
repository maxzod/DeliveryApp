<?php


namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UserLoginRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->phone = $data['$this->phone'];
    }
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=10)
     */
    public string $phone;
}