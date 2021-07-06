<?php


namespace App\Transformers;


use App\Dto\ComplaintsResponse;
use App\Entity\Complaints;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ComplaintTransformer implements \Symfony\Component\Form\DataTransformerInterface
{
    public function __construct(private UserTransformer $userTransformer, private OrderTransformer $orderTransformer)
    {
    }

    /**
     * @var Complaints[]|Complaints $value
     * @return ComplaintsResponse[]|ComplaintsResponse
     */
    public function transform($value) : array|ComplaintsResponse
    {
        if($value instanceof Complaints)
        {
            return $this->transformSingle($value);
        }
        $response = [];
        foreach ($value as $complaint)
        {
            array_push($response, $this->transformSingle($complaint));
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

    private function transformSingle(Complaints $complaint)
    {
        $response = new ComplaintsResponse();
        $response->id = $complaint->getId();
        $response->title = $complaint->getTitle();
        $response->message = $complaint->getMessage();
        $response->order = $this->orderTransformer->transform($complaint->getTheOrder());
        $response->owner = $this->userTransformer->transform($complaint->getOwner());
        return $response;
    }
}