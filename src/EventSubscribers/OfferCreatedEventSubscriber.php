<?php


namespace App\EventSubscribers;


use App\Entity\Offer;
use App\Messages\Notification;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class OfferCreatedEventSubscriber
{
    private MessageBusInterface $bus;
    private TranslatorInterface $translator;

    public function __construct(MessageBusInterface $bus, TranslatorInterface $translator)
    {
        $this->bus = $bus;
        $this->translator = $translator;
    }

    public function postPersist(Offer $offer, LifecycleEventArgs $event): void
    {
        $order = $offer->getTheOrder();
        $this->bus->dispatch(new Notification($this->translator->trans('order_client_noti_title', [], 'api'), $this->translator->trans('order_client_noti_body', [], 'api'), $order->getOwner()->getId(), "new"));
    }
}