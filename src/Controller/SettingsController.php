<?php


namespace App\Controller;


use App\Dto\SettingsResponse;
use App\Entity\Setting;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class SettingsController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route(name="settings.get", path="/api/settings")
     */
    public function __invoke()
    {
        /**
         * @var Setting $settings
         */
        $settings = $this->entityManager->getRepository(Setting::class)->find(1);
        $response = new SettingsResponse();
        $response->id = 1;
        $response->name = $settings->getName();
        $response->commission = $settings->getCommission();
        $response->logo = $settings->getLogo();
        $response->terms_conditions = $settings->getTermsConditions() ?? "";
        return new JsonResponse($this->serializer->deserialize($this->serializer->serialize($response, JsonEncoder::FORMAT), 'array', 'json'));
    }
}