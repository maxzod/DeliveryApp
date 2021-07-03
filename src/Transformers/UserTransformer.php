<?php


namespace App\Transformers;


use App\Dto\UserResponse;
use App\Entity\User;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserTransformer implements \Symfony\Component\Form\DataTransformerInterface
{

    /**
     * @var User $value
     * @return UserResponse
     */
    public function transform($value) : UserResponse
    {
        $user = new UserResponse();
        $user->id = $value->getId();
        $user->name = $value->getName();
        $user->email = $value->getEmail();
        $user->role = $value->getRoles()[0] == "ROLE_CLIENT" ? 0 : 1;
        $user->account_status = $value->getAccountStatus();
        $user->status_note = $value->getStatusNote();
        $user->phone = $value->getPhone();
        $user->stcPay = $value->getStcpay();
        $user->gender = $value->getGender();
        $user->latitude = $value->getLatitude();
        $user->longitude = $value->getLongitude();
        $user->stars = $value->getAvgReviews();
        $user->image = $value->getImage();
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        // TODO: Implement reverseTransform() method.
    }
}