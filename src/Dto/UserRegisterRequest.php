<?php


namespace App\Dto;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManager;

/**
 * Class UserRegisterDto
 * @package App\Dto
 */
class UserRegisterRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->phone = $data['phone'];
        $this->stcpay = $data['stcpay'];
        $this->latitude = $data['latitude'];
        $this->longitude = $data['longitude'];
        $this->role = $data['role'];
        $this->gender = $data['gender'];
        $this->image_id = $data['image_id'];
        $this->form_img = $data['form_img'];
        $this->front_img = $data['front_img'];
        $this->back_img = $data['back_img'];
        $this->license_img = $data['license_img'];
        $this->id_card_img = $data['id_card_img'];
        $this->id_number = $data['id_number'];
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
    public string $password;
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Regex(pattern="/^966[0-9]{9}/")
     */
    public string $phone;
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Regex(pattern="/^966[0-9]{9}/")
     */
    public string $stcpay;
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
     * @Assert\Range(min="0", max="1")
     */
    public int $role;
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Choice({"male", "female"})
     */
    public string $gender;

    public ?int $image_id;
    /**
     * @Assert\Expression(
     *     "not (this.role == 1 and this.form_img == null)",
     *     message="If role = 1, form_img should not be null"
     * )
     */
    public ?int $form_img;
    /**
     * @Assert\Expression(
     *     "not (this.role == 1 and this.license_img == null)",
     *     message="If role = 1, license_img should not be null"
     * )
     */
    public ?int $license_img;
    /**
     * @Assert\Expression(
     *     "not (this.role == 1 and this.front_img == null)",
     *     message="If role = 1, front_img should not be null"
     * )
     */
    public ?int $front_img;
    /**
     * @Assert\Expression(
     *     "not (this.role == 1 and this.back_img == null)",
     *     message="If role = 1, back_img should not be null"
     * )
     */
    public ?int $back_img;
    /**
     * @Assert\Expression(
     *     "not (this.role == 1 and this.id_card_img == null)",
     *     message="If role = 1, id_card_img should not be null"
     * )
     */
    public ?int $id_card_img;
    /**
     * @Assert\Expression(
     *     "not (this.role == 1 and this.id_number == null)",
     *     message="If role = 1, id_number should not be null"
     * )
     */
    public ?string $id_number;
}