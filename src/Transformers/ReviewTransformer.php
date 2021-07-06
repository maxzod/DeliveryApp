<?php


namespace App\Transformers;


use App\Dto\ReviewResponse;
use App\Entity\Review;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ReviewTransformer implements \Symfony\Component\Form\DataTransformerInterface
{
    public function __construct(private UserTransformer $userTransformer)
    {
    }

    /**
     * @var Review[]|Review $value
     * @return ReviewResponse[]|ReviewResponse
     */
    public function transform($value): array|ReviewResponse
    {
        if($value instanceof Review)
        {
            return $this->transformSingle($value);
        }
        $response = [];
        foreach ($value as $review)
        {
            array_push($response, $this->trasnformSingle($review));
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

    private function trasnformSingle(Review $review)
    {
        $response = new ReviewResponse();
        $response->id = $review->getId();
        $response->stars = $review->getStars();
        $response->comment = $review->getComment();
        $response->reviewer = $this->userTransformer->transform($review->getReviewer());
        $response->reviewed = $this->userTransformer->transform($review->getReviewed());
        return $response;
    }
}