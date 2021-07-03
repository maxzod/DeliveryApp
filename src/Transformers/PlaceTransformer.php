<?php


namespace App\Transformers;


use App\Dto\PlaceDto;
use App\Entity\DropPlace;
use App\Entity\OrderPlace;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PlaceTransformer implements \Symfony\Component\Form\DataTransformerInterface
{

    /**
     * @var OrderPlace|DropPlace $value
     * @return PlaceDto
     */
    public function transform($value): PlaceDto
    {
        $response = new PlaceDto();
        $response->name = $value->getName();
        $response->longitude = $value->getLongitude();
        $response->latitude = $value->getLatitude();
        $response->address = $value->getAddress();
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