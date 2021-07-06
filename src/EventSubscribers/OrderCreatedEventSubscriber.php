<?php


namespace App\EventSubscribers;


use App\Entity\Order;
use App\Entity\User;
use App\Messages\Notification;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderCreatedEventSubscriber
{
    public function __construct(private EntityManagerInterface $entityManager, private MessageBusInterface $bus, private TranslatorInterface $translator)
    {
    }

    public function postPersist(Order $order, LifecycleEventArgs $event): void
    {
        /**
         * @var UserRepository $drepo
         */
        $driversRepo = $this->entityManager->getRepository(User::class);

        $qb = $driversRepo->createQueryBuilder('d');
        $driversIds = $qb->where(
            $qb->expr()->andX(
                $qb->expr()->like('d.roles', ':role'),
                $qb->expr()->eq('d.account_status', ':account_status'),
                $qb->expr()->isNotNull('d.mobile_token')))
            ->setParameter('role', '["ROLE_DRIVER"]')
            ->setParameter('account_status', User::STATUS_ACTIVE)
            ->select('d.id')
            ->getQuery()->getArrayResult();
        $ids = array_column($driversIds, "id");
        foreach ($ids as $id)
        {
            $this->bus->dispatch(new Notification($this->translator->trans('new_orders_noti_title', [], 'api'), $this->translator->trans('new_orders_noti_body', [], 'api'), $id, "new"));
        }
    }
}