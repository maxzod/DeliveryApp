<?php


namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->latitude = $data['latitude'];
        $this->longitude = $data['longitude'];
        $this->gender = $data['gender'];
        $this->image_id = $data['image_id'];
    }
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public string $name;
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Email()
     */
    public string $email;
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public string $latitude;
    /**
     * @Assert\NotBlank(allowNull=false)
     */
    public string $longitude;
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Choice({"male", "female"})
     */
    public string $gender;
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\GreaterThan(value="0")
     */
    public int $image_id;
}