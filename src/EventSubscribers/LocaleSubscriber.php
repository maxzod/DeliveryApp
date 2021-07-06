<?php


namespace App\EventSubscribers;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $accept_language = $request->headers->get("accept-lang");
        if (empty($accept_language)) {
            return;
        }
        if(!in_array($accept_language, ['ar', 'en'])){
            return;
        }

        $request->setLocale($accept_language);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}