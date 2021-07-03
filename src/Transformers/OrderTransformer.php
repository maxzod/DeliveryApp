<?php


namespace App\Transformers;


use App\Dto\OrderResponse;
use App\Entity\Order;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class OrderTransformer implements DataTransformerInterface
{

    public function __construct(private UserTransformer $userTransformer, private ProductTransformer $productTransformer,
                                private PlaceTransformer $placeTransformer, private OrderBillTransformer $billTransformer,
                                private CouponTransformer $couponTransformer)
    {
    }

    /**
     * @param Order[]|Order $value
     * @return array|OrderResponse
     */
    public function transform($value) : array|OrderResponse
    {
        if($value instanceof Order){
            return $this->transformSingleOrder($value);
        }
        $response = [];
        foreach ($value as $order)
        {
            array_push($response, $this->transformSingleOrder($order));
        }
        return $response;
    }

    public function reverseTransform($value)
    {
        // TODO: Implement reverseTransform() method.
    }

    private function transformSingleOrder(Order $value): OrderResponse
    {
        $response = new OrderResponse();
        $response->id = $value->getId();
        $response->price = $value->getPrice();
        $response->status = $value->getStatus();
        $response->note = $value->getNote();
        $response->createdAt = $value->getCreatedAt()->format("yyyy-mm-dd hh:mm:ss");
        $response->conversation = $value->getConversation()?->getId();
        $response->owner = $this->userTransformer->transform($value->getOwner());
        $response->driver = $value->getDriver() ? $this->userTransformer->transform($value->getDriver()) : null;
        $response->products = $this->productTransformer->transform($value->getProducts());
        $response->place = $this->placeTransformer->transform($value->getPlace());
        $response->drop_place = $this->placeTransformer->transform($value->getDropPlace());
        $response->orderBills = $this->billTransformer->transform($value->getOrderBills());
        $response->coupon = $value->getCoupon() ? $this->couponTransformer->transform($value->getCoupon()) : null;
        return $response;
    }
}