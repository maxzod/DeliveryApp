<?php


namespace App\Controller;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\CheckCodeRequest;
use App\Dto\DriverInfoResponse;
use App\Dto\UpdateUserRequest;
use App\Dto\UserLoginDto;
use App\Dto\UserLoginRequest;
use App\Dto\UserRegisterDto;
use App\Dto\UserRegisterRequest;
use App\Dto\UserResponse;
use App\Dto\UserWithTokenResponse;
use App\Entity\MediaObject;
use App\Entity\User;
use App\Repository\MediaObjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use JetBrains\PhpStorm\Pure;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Doctrine\ORM\QueryBuilder;

class AuthController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordEncoder;
    /**
     * @var JWTTokenManagerInterface
     */
    private JWTTokenManagerInterface $tokenManager;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private Security $security;
    private MediaObjectRepository $mediaObjectRepository;
    private TranslatorInterface $translator;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        JWTTokenManagerInterface $tokenManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        Security $security,
        MediaObjectRepository $mediaObjectRepository,
        TranslatorInterface $translator
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenManager = $tokenManager;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->security = $security;
        $this->mediaObjectRepository = $mediaObjectRepository;
        $this->translator = $translator;
    }

    /**
     * @Route(name="auth.register", methods={"POST"}, path="/api/register")
     * @param UserRegisterRequest $request
     * @return JsonResponse|UserResponse
     */
    public function register(UserRegisterRequest $request): JsonResponse|UserResponse
    {
        $roles = [['ROLE_CLIENT'], ['ROLE_DRIVER']];
        if (!array_key_exists($request->role, $roles)) {
            return new JsonResponse(['error' => $this->translator->trans('role_not_found', [], 'api')], 422);
        }
        $repo = $this->entityManager->getRepository(User::class);
        $exists = $repo->findOneBy(['email' => $request->email]);
        if ($exists != null) {
            return new JsonResponse(['error' => $this->translator->trans('email_taken', [], 'api')], 422);
        }
        $exists = $repo->findOneBy(['phone' => $request->phone]);
        if ($exists != null) {
            return new JsonResponse(['error' => $this->translator->trans('phone_taken', [], 'api')], 422);
        }
        $image = null;
        if (!empty($request->image_id)) {
            $image = $this->mediaObjectRepository->find($request->image_id);
            if (is_null($image)) {
                return new JsonResponse(['error' => $this->translator->trans('image_not_found', [], 'api')], 422);
            }
        }

        if ($request->role == 0) {
            $user = User::createClient(
                $request->name,
                $request->email,
                $request->gender,
                $image,
                $request->longitude,
                $request->latitude,
                $request->phone,
                $request->stcpay
            );
        } else {
            $form_img = $this->mediaObjectRepository->find($request->form_img);
            $license_img = $this->mediaObjectRepository->find($request->license_img);
            $front_img = $this->mediaObjectRepository->find($request->front_img);
            $back_img = $this->mediaObjectRepository->find($request->back_img);
            $id_card_img = $this->mediaObjectRepository->find($request->id_card_img);
            $id_number = $request->id_number;
            $user = User::createDriver(
                $request->name,
                $request->email,
                $request->gender,
                $image,
                $request->longitude,
                $request->latitude,
                $request->phone,
                $request->stcpay,
                $form_img,
                $front_img,
                $back_img,
                $license_img,
                $id_card_img,
                $id_number
            );
        }

        $this->passwordEncoder->hashPassword($user, $request->password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $userResponse = $this->mappedUser($user);
        return new JsonResponse($this->serializer->deserialize($this->serializer->serialize($userResponse, JsonEncoder::FORMAT), 'array', 'json'), 201);
    }

    /**
     * @Route(name="auth.checkcode", methods={"POST"}, path="/api/checkcode")
     * @param CheckCodeRequest $request
     * @return UserWithTokenResponse|JsonResponse
     * @throws NonUniqueResultException
     */
    public function checkCode(CheckCodeRequest $request): JsonResponse|UserWithTokenResponse
    {
        /**
         * @var UserRepository $userRepo
         */
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $qb = $userRepo->createQueryBuilder('u');
        /**
         * @var User $user
         */
        try {
            $user = $qb->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('u.phone', $request->phone),
                    $qb->expr()->eq('u.code', $request->code)
                )
            )->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return new JsonResponse(['error' => $this->translator->trans('wrong_code', [], 'api')], 422);
        }
        $user->setMobileToken($request->mobile_token);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $token = $this->tokenManager->create($user);
        $response = new UserWithTokenResponse();
        $response->token = $token;
        $userDto = $this->mappedUser($user);
        $response->user = $userDto;

        return new JsonResponse($this->serializer->deserialize($this->serializer->serialize($response, JsonEncoder::FORMAT), 'array', 'json'));
    }

    /**
     * @param UserLoginRequest $request
     * @return JsonResponse
     * @Route(name="auth.login", methods={"post"}, path="/api/login")
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        /**
         * @var UserRepository $userRepo
         */
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->findOneBy(['phone' => $request->phone]);
        if ($user == null) {
            return new JsonResponse(['error' => $this->translator->trans('no_user_for_phone', [], 'api')], 404);
        }
        $user->setCode(1111); //change in production
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonResponse(['message' => $this->translator->trans('code_sent', [], 'api')]);
    }

    /**
     * @Route(name="auth.logout", methods={"DELETE"}, path="/api/logout")
     */
    public function logout(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        if ($user == null) {
            return new JsonResponse(['error' => $this->translator->trans('not_found', [], 'api')], 404);
        }
        $user->setMobileToken(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonResponse(null, 204);
    }
    /**
     * @Route(name="auth.user", methods={"GET"}, path="/api/user")
     */
    public function getUserByToken(): JsonResponse
    {
        if ($this->security->getUser() == null) {
            return new JsonResponse(['error' => $this->translator->trans('un_auth', [], 'api')], 401);
        }
        $response = $this->mappedUser($this->security->getUser());
        return new JsonResponse($this->serializer->deserialize($this->serializer->serialize($response, JsonEncoder::FORMAT), 'array', 'json'));
    }

    /**
     * @Route(name="driver.info", path="/api/user/info", methods={"GET"})
     */
    public function extraDriverInfo(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        if ($user == null || (!in_array("ROLE_DRIVER", $user->getRoles()) && !in_array("ROLE_CLIENT", $user->getRoles()))) {
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        /**
         * @var UserRepository $repo
         */
        $repo = $this->entityManager->getRepository(User::class);
        $info = $repo->getUserExtraInfo($user->getId());
        if (in_array("ROLE_DRIVER", $user->getRoles())) {
            $info[0]['orders_count'] = count($user->getDriverOrders());
        } else {
            $info[0]['orders_count'] = count($user->getOrders());
        }
        $response = $this->serializer->deserialize($this->serializer->serialize($info[0], 'json'), DriverInfoResponse::class, 'json');

        return new JsonResponse($response);
    }

    /**
     * @param UpdateUserRequest $request
     * @return JsonResponse
     * @Route(name="user.update", path="api/user", methods={"POST"})
     */
    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        if ($user == null) {
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }

        $user->setName($request->name);
        $user->setEmail($request->email);
        $user->setLatitude($request->latitude);
        $user->setLongitude($request->longitude);
        $user->setGender($request->gender);
        $image = $this->entityManager->getRepository(MediaObject::class)->find($request->image_id);
        if ($image != null) {
            $user->image = $image;
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $response = $this->mappedUser($user);
        return new JsonResponse($this->serializer->deserialize($this->serializer->serialize($response, JsonEncoder::FORMAT), 'array', 'json'));
    }
    /**
     * @param User $user
     * @return UserResponse
     */
    #[Pure] private function mappedUser(User $user): UserResponse
    {
        $userResponse = new UserResponse();
        $userResponse->id = $user->getId();
        $userResponse->name = $user->getName();
        $userResponse->email = $user->getEmail();
        $userResponse->role = $user->getRoles() == ["ROLE_CLIENT"] ? 0 : 1;
        $userResponse->phone = $user->getPhone();
        $userResponse->stcPay = $user->getStcpay();
        $userResponse->account_status = $user->getAccountStatus();
        $userResponse->status_note = $user->getStatusNote() == null ? "" : $user->getStatusNote();
        $userResponse->latitude = $user->getLatitude();
        $userResponse->longitude = $user->getLongitude();
        $userResponse->gender = $user->getGender();
        $userResponse->image = $user->image;
        return $userResponse;
    }
}