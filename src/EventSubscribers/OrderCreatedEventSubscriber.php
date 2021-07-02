<?php


namespace App\EventSubscribers;


use App\Entity\Order;
use Doctrine\ORM\Event\LifecycleEventArgs;

class OrderCreatedEventSubscriber
{
    public function postPersist(Order $order, LifecycleEventArgs $event): void
    {
        //TODO:create a notification to drivers
    }
}