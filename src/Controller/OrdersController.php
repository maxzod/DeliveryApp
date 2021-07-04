<?php

namespace App\Controller;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\OrderBillRequest;
use App\Dto\OrderRequest;
use App\Dto\OrderResponse;
use App\Entity\Bill;
use App\Entity\Conversation;
use App\Entity\Coupon;
use App\Entity\DropPlace;
use App\Entity\Offer;
use App\Entity\OrderPlace;
use App\Entity\Product;
use App\Entity\User;
use App\Messages\Notification;
use App\Repository\OrderRepository;
use App\Transformers\OrderTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;
use App\Repository\MediaObjectRepository;
use DateTime;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as SSecurity;

class OrdersController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;
    private SerializerInterface $serializer;
    private TranslatorInterface $translator;
    private MediaObjectRepository $mediaObjectRepository;
    private ValidatorInterface $validator;
    private OrderRepository $orderRepository;
    private OrderTransformer $orderTransformer;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        MessageBusInterface $bus,
        SerializerInterface $serializer,
        TranslatorInterface $translator,
        MediaObjectRepository $mediaObjectRepository,
        OrderRepository $orderRepository,
        ValidatorInterface $validator,
        OrderTransformer $orderTransformer
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->bus = $bus;
        $this->serializer = $serializer;
        $this->translator = $translator;
        $this->mediaObjectRepository = $mediaObjectRepository;
        $this->validator = $validator;
        $this->orderRepository = $orderRepository;
        $this->orderTransformer = $orderTransformer;
    }

    /**
     * @Route(name="orders.index", path="/api/orders", methods={"GET"})
     * @SSecurity("(is_granted('ROLE_CLIENT') or is_granted('ROLE_DRIVER')) and user.getAccountStatus() == 1")
     */
    public function index(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        if(in_array("ROLE_CLIENT", $user->getRoles()))
        {
            $orders = $this->orderRepository->getClientOrders($user, $request->query->getInt('page', 1), $request->query->getInt('status', 1));
        }else{
            $orders = $this->orderRepository->getAvailableOrders($request->query->getInt('page', 1));
        }
        $data = $this->orderTransformer->transform($orders);
        return new JsonResponse($this->serializer->serialize($data, 'json'), 200,json: true);
    }
    /**
     * @Route(name="orders.store", path="/api/orders",methods={"POST"})
     * @SSecurity("is_granted('ROLE_CLIENT') and user.getAccountStatus() == 1")
     */
    public function store(OrderRequest $request)
    {
        $coupon = null;
        if(isset($request->coupon) && $request->coupon != "")
        {
            $coupon = $this->entityManager->getRepository(Coupon::class)->findOneBy(['code' => $request->coupon]);
            if($coupon == null)
            {
                return $this->json(['error' => "invalid coupon code"], 400);
            }
        }
        $place = OrderPlace::create($request->place->name, $request->place->longitude, $request->place->latitude, $request->place->address);
        $dropPlace = DropPlace::create($request->drop_place->name, $request->drop_place->longitude, $request->drop_place->latitude, $request->drop_place->address);
        $products = [];
        foreach ($request->products as $product)
        {
            $prd = new Product();
            $prd->setName($product->name);
            $prd->setQuantity($product->quantity);
            $prd->setImage($this->mediaObjectRepository->findOneBy(['id' => $product->image]));
            array_push($products, $prd);
        }
        $order = Order::create($request->note, $place, $dropPlace, $products, $coupon);

        $this->entityManager->persist($order);
        $this->entityManager->flush();
        return $this->json(null, 204);
    }
    /**
     * @Route(name="driver.order.arrived", path="/api/orders/{id}/arrived", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function orderArrived(int $id): JsonResponse
    {
        /**
         * @var Order $order
         */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['id' => $id]);
        if ($order->getDriver() != $this->security->getUser()) {
            return new JsonResponse(['error' => 'access denied'], 403);
        }
        $this->bus->dispatch(new Notification($this->translator->trans('arrive_noti_title', [], 'api'), $this->translator->trans('arrive_noti_body', [], 'api'), $order->getOwner()->getId(), "new"));
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(
     *     path="/api/orders/{id}/offers",
     *      name="client.order.offers",
     *      methods={"GET"}
     *     )
     */
    public function getOffers(int $orderId)
    {
        return $this->json($this->entityManager->getRepository(Offer::class)->findBy(['order.id' => $orderId]));
    }

    /**
     * @param int $id
     * @return JsonResponse|Response
     * @Route(
     *     path="/api/offer/{id}/accept",
     *      name="client.offer.accept",
     *      methods={"GET"}
     *     )
     */
    public function acceptOffer(int $id)
    {
        /**
         * @var Offer $offer
         */
        $offer = $this->entityManager->getRepository(Offer::class)->find($id);
        if ($offer == null) {
            return new JsonResponse(['error' => $this->translator->trans('not_found', [], 'api')], 404);
        }
        $order = $offer->getTheOrder();
        if ($order->getOwner() != $this->security->getUser()) {
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        if ($order->getStatus() != Order::STATUS_WAITING) {
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 422);
        }
         $order->setPrice($offer->getPrice());
        $order->setStatus(Order::STATUS_PROCESSING);
        $order->setDriver($offer->getDriver());
        // Create conversation between driver and client if not exists
        $repo = $this->entityManager->getRepository(Conversation::class);
        $conv = $repo->findConversationByUsers($this->security->getUser(), $offer->getDriver());
        if ($conv == null) {
            $conv = new Conversation();
            $conv->setDriver($offer->getDriver());
            $conv->setClient($this->security->getUser());
            $this->entityManager->persist($conv);
            $order->setConversation($conv);
        } else if (is_array($conv)) {
            $order->setConversation($conv[0]);
        }
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        $this->bus->dispatch(new Notification($this->translator->trans('offer_accepted_noti_title', [], 'api'), $this->translator->trans('offer_accepted_noti_body', [], 'api'), $offer->getDriver()->getId(), "new"));
        return new Response(null, 204);
    }

    /**
     * @Route(path="/api/orders/driver_orders", methods={"GET"}, name="driver.orders")
     */
    public function getDriverOrders(Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        if (null === $user || !in_array('ROLE_DRIVER', $user->getRoles())) {
            return new JsonResponse([]);
        }
        /**
         * @var OrderRepository $repo
         */
        $status = $request->query->getInt("status", Order::STATUS_PROCESSING);
        $page = $request->query->getInt("page", 1);
        $dbOrders = $this->orderRepository->getDriverOrders($user, $page, $status);
        $orders = $this->orderTransformer->transform($dbOrders);
        return new JsonResponse($this->serializer->serialize($orders, 'json'), 200, [], false);
    }

    /**
     * @Route(
     *     path="/api/orders/{id}/new_bill",
     *      name="client.order.new_bill",
     *      methods={"POST"}
     *     )
     * @param int $id
     * @param OrderBillRequest $request
     * @return JsonResponse
     */
    public function addOrderBill(int $id,  OrderBillRequest $request): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if ($order == null) {
            return new JsonResponse(['error' => $this->translator->trans('not_found', [], 'api')], 404);
        }
        $orderBill = new Bill;
        $orderBill->setPrice($request->price);
        $image = $this->mediaObjectRepository->find($request->image_id);
        if (is_null($image)) {
            return new JsonResponse(['error' => $this->translator->trans('image_not_found', [], 'api')], 422);
        }
        $totalPrice = $request->price;
        foreach ($order->getOrderBills() as $bill) {
            $totalPrice += $bill->getPrice();
        }
        $order->setPrice($totalPrice);
        $orderBill->setImage($image);
        $orderBill->setTheOrder($order);
        $orderBill->setCreatedAt(new DateTime('now'));
        $this->entityManager->persist($orderBill);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        return new JsonResponse(['success' => true, 'message' => $this->translator->trans('success')]);
    }
    /**
     * @Route(
     *     path="/api/bills/{id}",
     *      name="bills.delete",
     *      methods={"DELETE"}
     *     )
     * @param int $id
     * @return JsonResponse
     */
    public function deleteBill(int $id): JsonResponse
    {

        $bill = $this->entityManager->getRepository(Bill::class)->find($id);
        if ($bill == null) {
            return new JsonResponse(['error' => $this->translator->trans('not_found', [], 'api')], 404);
        }
        $order = $bill->getTheOrder();
        $price = $order->getPrice();
        $order->setPrice($price - $bill->getPrice());
        $this->entityManager->persist($order);
        $this->entityManager->remove($bill);
        $this->entityManager->flush();
        return
            new JsonResponse(['success' => true, 'message' => $this->translator->trans('success')]);
    }
//    /**
//     * @Route(
//     *     path="/api/orders/{id}",
//     *      name="retrieve.order",
//     *      methods={"GET"}
//     *     )
//     * @param int $id
//     * @return JsonResponse
//     * @throws ResourceClassNotSupportedException
//     */
//    public function retrieveOrder(int $id)
//    {
//        $user = $this->security->getUser();
//        if (null === $user || !in_array('ROLE_DRIVER', $user->getRoles())) {
//            return new JsonResponse([], 401);
//        }
//        $order = $this->entityManager->getRepository(Order::class)->find($id);
//        if ($order == null) {
//            return new JsonResponse(['error' => $this->translator->trans('not_found', [], 'api')], 404);
//        }
//
//        $order = $this->serializer->deserialize($this->serializer->serialize($order, 'json'), OrderResponse::class, 'json');
//        return new JsonResponse($order, 200, [], false);
//    }
}