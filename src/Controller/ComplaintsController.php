<?php


namespace App\Controller;


use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\OwnerEntitiesCollectionDataProvider;
use App\Entity\Complaints;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ComplaintsController extends AbstractController
{

//    /**
//     * @param OwnerEntitiesCollectionDataProvider $provider
//     * @Route(name="user.complaints", path="/api/complaints", methods={"GET"},defaults={
//     *     "_api_resource_class"= Complaints::class,
//     *     "_api_collection_operation_name"= "userComplaints"
//     *     })
//     * @return array|int|iterable|mixed[]|string
//     * @throws ResourceClassNotSupportedException
//     */
//    public function getUserComplaints(OwnerEntitiesCollectionDataProvider $provider)
//    {
//        return $provider->getCollection(Complaints::class, "userComplaints");
//    }
}