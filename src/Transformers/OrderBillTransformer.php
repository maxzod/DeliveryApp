<?php


namespace App\Transformers;


use App\Dto\ImageResponse;
use App\Dto\OrderBillResponse;
use App\Entity\Bill;
use Symfony\Component\Form\Exception\TransformationFailedException;

class OrderBillTransformer implements \Symfony\Component\Form\DataTransformerInterface
{

    /**
     * @var Bill[]|Bill $value
     * @return  OrderBillResponse[]|OrderBillResponse
     */
    public function transform($value): array|OrderBillResponse
    {
        if($value instanceof Bill){
            return $this->transformSingleBill($value);
        }
        $response = [];
        foreach ($value as $bill)
        {
            array_push($response, $this->transformSingleBill($bill));
        }
        return $response;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        // TODO: Implement reverseTransform() method.
    }

    private function transformSingleBill(Bill $value) : OrderBillResponse
    {
        $response = new OrderBillResponse();
        $response->id = $value->getId();
        $response->price = $value->getPrice();
        $response->image = new ImageResponse();
        $response->image->id = $value->getImage()?->getId();
        $response->image->path = $value->getImage()?->filePath;
        return $response;
    }
}