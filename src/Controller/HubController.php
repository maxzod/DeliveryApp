<?php

namespace App\Controller;

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

class HubController extends AbstractController
{
    /**
     * @Route("/", name="redir")
     */
    public function index(Request $request): Response
    {
        return $this->redirectToRoute('admin');
    }
    /**
     * @Route("/hub", name="hub")
     */
    public function hub(Request $request): Response
    {
        $hub_url = $this->getParameter('mercure.default_hub');
        $this->addLink($request, new Link('mercure', $hub_url));
        //$update = new Update(["index"], "hello");
        return $this->render('hub/index.html.twig', [
            'controller_name' => 'HubController',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/hub/update", name="hub.update")
     */
    public function getToken(Request $request, MessageBusInterface $bus) : JsonResponse
    {
        $update = new Update('http://localhost:8000/data', json_encode(['status' => 'working']));
        $bus->dispatch($update);
        return new JsonResponse(['status' => 'published!']);
    }
}
