<?php


namespace App\Controller;


use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\OwnerEntitiesCollectionDataProvider;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Transformers\NotificationTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as SSecurity;

class NotificationsController extends AbstractController
{
    public function __construct(private NotificationRepository $repository, private NotificationTransformer $transformer,
                                private SerializerInterface $serializer, private Security $security)
    {
    }

    #[Route(path: "/api/notifications", name: "user.notifications", methods: ["GET"])]
    /**
     * @SSecurity("(is_granted('ROLE_CLIENT') or is_granted('ROLE_DRIVER')) and user.getAccountStatus() == 1")
     */
    public function index()
    {
        $data = $this->repository->createQueryBuilder($this->security->getUser()->getId());
        $response = $this->transformer->transform($data);
        return new JsonResponse($this->serializer->serialize($response, 'json'), 200, json: true);
    }
}