<?php


namespace App\Controller;


use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\OwnerEntitiesCollectionDataProvider;
use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationsController extends AbstractController
{
//    /**
//     * @param OwnerEntitiesCollectionDataProvider $provider
//     * @return array|int|iterable|mixed[]|string
//     * @Route(name="user.notifications", path="api/notifications", methods={"get"},defaults={
//     *     "_api_resource_class"= Notification::class,
//     *     "_api_collection_operation_name"= "userNotifications"
//     *     })
//     */
//    public function getUserNotifications(OwnerEntitiesCollectionDataProvider $provider)
//    {
//        try {
//            return $provider->getCollection(Notification::class, "userNotifications");
//        } catch (ResourceClassNotSupportedException $e) {
//        }
//    }
}