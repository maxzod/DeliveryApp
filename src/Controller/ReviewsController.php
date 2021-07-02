<?php


namespace App\Controller;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataProvider\UserReviewsCollectionDataProvider;
use App\Dto\ReviewRequest;
use App\Entity\Order;
use App\Entity\Review;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReviewsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Security
     */
    private Security $security;
    private ValidatorInterface $validator;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, Security $security, ValidatorInterface $validator, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->validator = $validator;
        $this->translator = $translator;
    }
    /**
     * @param int $id
     * @param Request $request
     * @Route(name="order.review", path="/api/orders/{id}/review", methods={"POST"})
     */
    public function reviewOrder(int $id, ReviewRequest $request)
    {
        /**
         * @var Order $order
         */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['id' => $id]);
        $user = $this->security->getUser();
        /**
         * @var ArrayCollection $orderReviews
         */
        $orderReviews = $order->getReviews();
        $criteria = (new Criteria)->where(new Comparison("reviewer", "=", $user));
        $revs = $orderReviews->matching($criteria);
        if(count($revs) > 0){
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        $review = new Review();
        $review->setTheOrder($order);
        $review->setReviewer($user);
        if($order->getOwner() == $user){
            $review->setReviewed($order->getDriver());
        }
        elseif ($order->getDriver() == $user){
            $review->setReviewed($order->getOwner());
        }else{
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')],403);
        }
        $review->setStars($request->stars);
        $review->setComment($request->comment);
        $this->entityManager->persist($review);
        $this->entityManager->flush();
        return  new JsonResponse(['status' => $this->translator->trans('success', [], 'api')], 201);
    }

//    /**
//     * @Route(name="api.user.reviews", methods={"GET"}, path="/api/users/{id}/reviews"
//     * ,defaults={
//     *     "_api_resource_class"= Review::class,
//     *     "_api_collection_operation_name"= "getUserReviews"
//     *     }))
//     */
//    public function getUserReviews(int $id, UserReviewsCollectionDataProvider $provider){
//        return $provider->getCollection(Review::class, 'getUserReviews');
//    }

}