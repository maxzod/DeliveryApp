<?php


namespace App\Transformers;


use App\Dto\CouponResponse;
use App\Entity\Coupon;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CouponTransformer implements \Symfony\Component\Form\DataTransformerInterface
{

    /**
     * @var Coupon $value
     * @return CouponResponse
     */
    public function transform($value): CouponResponse
    {
        $response = new CouponResponse();
        $response->id = $value?->getId();
        $response->value = $value?->getValue();
        $response->isFixed = $value?->getIsFixedNumber();
        return $response;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        // TODO: Implement reverseTransform() method.
    }
}