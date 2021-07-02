<?php


namespace App\Dto;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ReviewRequest implements IRequestDTO
{
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->stars = $data['stars'];
        $this->comment = $data['comment'];
    }
    /**
     * @Assert\NotBlank(allowNull=false)
     * @Assert\Range(min="1", max="5")
     */
    public int $stars;
    /**
     * @Assert\NotBlank(allowNull=true)
     */
    public string $comment;
}