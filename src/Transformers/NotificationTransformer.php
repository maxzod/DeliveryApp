<?php


namespace App\Transformers;


use App\Dto\NotificationResponse;
use App\Entity\Notification;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NotificationTransformer implements \Symfony\Component\Form\DataTransformerInterface
{
    public function __construct(private UserTransformer $userTransformer)
    {
    }

    /**
     * @var Notification[]|Notification
     * @return NotificationResponse[]|NotificationResponse
     */
    public function transform($value) : array|NotificationResponse
    {
        if($value instanceof NotificationResponse)
        {
            return $this->transformSingle($value);
        }
        $response = [];
        foreach ($value as $item)
        {
            array_push($response, $this->transformSingle($item));
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

    private function transformSingle(Notification $item): NotificationResponse
    {
        $response = new NotificationResponse();
        $response->id = $item->getId();
        $response->title = $item->getTitle();
        $response->content = $item->getContent();
        $response->owner = $this->userTransformer->transform($item->getOwner());
        return $response;
    }
}