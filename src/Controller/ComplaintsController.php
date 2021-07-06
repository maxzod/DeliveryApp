<?php


namespace App\Controller;


use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\OwnerEntitiesCollectionDataProvider;
use App\Entity\Complaints;
use App\Repository\ComplaintsRepository;
use App\Transformers\ComplaintTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as SSecurity;
class ComplaintsController extends AbstractController
{
    public function __construct(private ComplaintsRepository $repository, private ComplaintTransformer $transformer, private SerializerInterface $serializer)
    {
    }
    #[Route(path: "/api/complaints", name: "user.complaints", methods: ["GET"])]
    /**
     * @SSecurity("(is_granted('ROLE_CLIENT') or is_granted('ROLE_DRIVER')) and user.getAccountStatus() == 1")
     */
    public function index()
    {
        $complaints = $this->repository->getUserComplaints($this->getUser()->getId());
        $response = $this->transformer->transform($complaints);
        return new JsonResponse($this->serializer->serialize($response, 'json'), 200, json: true);
    }
}