<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Setting;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class ClientTakeOrderController extends AbstractController
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var Security
     */
    private Security $security;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, Security $security, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->translator = $translator;
    }

    /**
     * @param Order $order
     * @return Order|JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     path="/api/client/orders/{id}/take_order",
     *      name="client.order.take",
     *      methods={"GET"},
     *     defaults={
     *     "_api_resource_class"= Order::class,
     *     "_api_collection_operation_name"= "takeOrder"
     *     }))
     */
    public function __invoke(int $id)
    {
        /**
         * @var Order $order
         */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['id' => $id]);
        /**
         * @var Setting $settings
         */
        $settings = $this->entityManager->getRepository(Setting::class)->find(1);
        if ($order == null) {
            return new JsonResponse(['error' => $this->translator->trans('not_found', [], 'api')], 404);
        }
        if ($order->getStatus() != Order::STATUS_PROCESSING) {
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        if ($order->getOwner() != $this->security->getUser()) {
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        $order->setStatus(Order::STATUS_DONE);
        $deliveryPrice = $order->getPrice();
        if ($order->getCoupon() != null) {
            $deliveryPrice = $deliveryPrice - ($deliveryPrice * ($order->getCoupon()->getValue() / 100));
        }
        $app_commission = $settings->getCommission() / 100;
        $app_earnings = $app_commission * $deliveryPrice;
        $driver_earnings = $deliveryPrice - $app_earnings;
        $order->getDriver()->setAccountBalance($order->getDriver()->getAccountBalance() + $app_earnings);
        $order->getDriver()->setTotalDeliveryFees($order->getDriver()->getTotalDeliveryFees() + $driver_earnings);
        if ($order->getDriver()->getTotalDeliveryFees() + $driver_earnings > 100)
            $this->entityManager->persist($order);
        $this->entityManager->flush();
        return new JsonResponse([
            'id' => $order->getId(),
            'order_status' => Order::STATUS_DONE
        ]);
    }
}