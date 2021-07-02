<?php

namespace App\Controller;

use App\Dto\DriverOfferRequest;
use App\Entity\Offer;
use App\Entity\Order;
use App\Entity\User;
use App\Messages\Notification;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class DriverMakeOfferController extends AbstractController
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, Security $security,  TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->translator = $translator;
    }

    /**
     * @param int $id
     * @param DriverOfferRequest $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route(
     *     path="/api/driver/orders/{id}/offer",
     *      name="driver.order.offer",
     *      methods={"POST"}
     *     )
     */
    public function __invoke(int $id, DriverOfferRequest $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        /**
         * @var Order $order
         */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['id' => $id]);
        [$valid, $err] = $this->isOrderOfferable($order, $user);
        if(!$valid) {
            return $err;
        }
        if ($user->getAccountBalance() >= 100) {
            return new JsonResponse(['error' => $this->translator->trans('pay_fees_err', [], 'api')], 403);
        }
        $offer = new Offer();
        $offer->setPrice($request->price);
        $offer->setDriver($user);
        $offer->setTheOrder($order);
        $this->entityManager->persist($offer);
        $this->entityManager->flush();
        return new JsonResponse(null, 204);
    }

    private function isOrderOfferable(Order $order, User $user): array
    {
        $errResponse = null;
        if ($order == null) {
            $errResponse = new JsonResponse(['error' => $this->translator->trans('not_found', [], 'api')], 404);
        }
        if (!in_array('ROLE_DRIVER', $user->getRoles())) {
            $errResponse = new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        if ($user->getAccountStatus() != User::STATUS_ACTIVE) {
            $errResponse = new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }

        if ($order->getStatus() == Order::STATUS_PROCESSING) {
            $errResponse = new JsonResponse(['error' => $this->translator->trans('order_in_process_err', [], 'api')], 422);
        }
        if ($order->getOffers()->count() == 6) {
            $errResponse = new JsonResponse(['error' => $this->translator->trans('order_reach_max_offers', [], 'api')], 422);
        }
        $offers = $order->getOffers();
        $criteria = (new Criteria)->where(new Comparison("driver", "=", $user));
        $offs = $offers->matching($criteria);
        if (count($offs) > 0) {
            $errResponse = new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 422);
        }
        return $errResponse == null ? [false, $errResponse] : [true, null];
    }
}