<?php


namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CheckCodeRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->phone = $data['phone'];
        $this->code = $data['code'];
        $this->mobile_token = $data['mobile_token'];
    }

    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Length(min=10)
     */
    public string $phone;
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=4, max=4)
     */
    public int $code;
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public string $mobile_token;
}