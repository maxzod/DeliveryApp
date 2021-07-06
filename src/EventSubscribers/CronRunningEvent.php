<?php


namespace App\EventSubscribers;

//php -d register_argc_argv=1 /home/admin/ghadhasymf.ga/bin/console messenger:consume async --memory-limit=128M
//JWT_KEY='3789000Abdo' ADDR='ghadhasymf.ga:3000' ALLOW_ANONYMOUS=1 CORS_ALLOWED_ORIGINS=* /home/admin/ghadhasymf.ga/bin/mercure
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;

class CronRunningEvent implements EventSubscriberInterface
{
    public function onWorkerRunning(WorkerRunningEvent $event): void
    {
//        if ($event->isWorkerIdle()) {
//            $event->getWorker()->stop();
//        }
    }
    public static function getSubscribedEvents()
    {
        return [
            WorkerRunningEvent::class => 'onWorkerRunning',
        ];
    }
}