<?php


namespace App\Transformers;


use App\Dto\ImageResponse;
use App\Dto\ProductResponse;
use App\Entity\Product;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductTransformer implements \Symfony\Component\Form\DataTransformerInterface
{

    /**
     * @var Product[]|Product $value
     * @return ProductResponse[]|ProductResponse
     */
    public function transform($value): ProductResponse|array
    {
        if($value instanceof Product)
        {
            return $this->transformSingleProduct($value);
        }
        $response = [];
        foreach ($value as $product) {
            array_push($response, $this->transformSingleProduct($product));
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
    #[Pure]
    private function transformSingleProduct(Product $product): ProductResponse
    {
        $response = new ProductResponse();
        $response->id = $product->getId();
        $response->name = $product->getName();
        $response->quantity = $product->getQuantity();
        $response->image = new ImageResponse();
        $response->image->id = $product->getImage()?->getId();
        $response->image->path = $product->getImage()?->getFilePath();
        return $response;
    }
}