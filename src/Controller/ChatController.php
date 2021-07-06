<?php


namespace App\Controller;


use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\ConversationsDataProvider;
use App\Dto\MessageRequest;
use App\Dto\MessageResponse;
use App\Entity\MediaObject;
use App\Entity\Message;
use App\Repository\ConversationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Conversation;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Doctrine\ORM\QueryBuilder;

class ChatController extends AbstractController
{
    private Security $security;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private TranslatorInterface $translator;

    public function __construct(Security $security, SerializerInterface $serializer, ValidatorInterface $validator, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->translator = $translator;
    }


    /**
     * @param int $id
     * @return array|JsonResponse
     * @Route(path="/api/conversations/{id}/messages",
     *      name="user.conversation.messages",
     *      methods={"GET"},
     *      defaults={
     *     "_api_resource_class"= Conversation::class,
     *     "_api_collection_operation_name"= "getConversationMessages"
     *     })
     */
    public function getConversationMessages(int $id, Request $request, Discovery $discovery, Authorization $authorization)
    {
        $user = $this->security->getUser();
        if($user == null)
        {
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        /**
         * @var ConversationRepository $repo
         */
        $repo = $this->getDoctrine()->getRepository(Conversation::class);
        if(!in_array("ROLE_DRIVER", $user->getRoles()) && !in_array("ROLE_CLIENT", $user->getRoles())){
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        $conv = $repo->getConversationMessages($id, $request->query->getInt("page"));
        $response = new JsonResponse();
        $discovery->addLink($request);

        $response->headers->setCookie(
            $authorization->createCookie($request,  [sprintf("http://ghadhasymf.ga/api/conversations/%s/messages", $id)])
        );
        if(!array_key_exists(0, $conv) && !array_key_exists('client', $conv[0])){
            return $response->setData([]);
        }
        if($conv[0]['client']['id'] != $user->getId() && $conv[0]['driver']['id'] != $user->getId())
        {
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        for ($i = 0; $i < count($conv[0]['messages']); $i++)
        {
            $conv[0]['messages'][$i]['sender_id'] = $conv[0]['messages'][$i]['sender']['id'];
        }
        $messages = [];
        foreach ($conv[0]['messages'] as $message){
            array_push($messages, $this->serializer->deserialize($this->serializer->serialize($message, 'json'), MessageResponse::class, 'json'));
        }
        return $response->setData($messages);
    }

    /**
     * @param int $id
     * @param MessageRequest $request
     * @param MessageBusInterface $bus
     * @return JsonResponse
     * @Route(path="/api/conversations/{id}/messages",
     *      name="user.conversation.messages.add",
     *      methods={"POST"})
     */
    public function addMessage(int $id, MessageRequest $request, MessageBusInterface $bus): JsonResponse
    {
        /**
         * @var Conversation $conv
         */
        $conv = $this->getDoctrine()->getRepository(Conversation::class)->findOneBy(['id' => $id]);
        if($conv == null)
        {
            return new JsonResponse(['error' => $this->translator->trans('not_found', [], 'api')], 404);
        }
        if($conv->getClient() != $this->security->getUser() && $conv->getDriver() != $this->security->getUser()){
            return new JsonResponse(['error' => $this->translator->trans('access_denied', [], 'api')], 403);
        }
        $message = new Message();
        $message->setSender($this->security->getUser());
        if($request->type == Message::TYPE_TEXT){
            $message->setContent($request->content);
            $message->setType($request->type);
        }
        else if($request->type == Message::TYPE_IMG)
        {
            /**
             * @var MediaObject $img
             */
            $img = $this->getDoctrine()->getRepository(MediaObject::class)->findOneBy(['id' => $request->content]);
            if($img == null){
                return new JsonResponse(['error' => $this->translator->trans('image_not_found', [], 'api')], 404);
            }
            $message->setContent($img->getFilePath());
            $message->setType($request->type);
        }
        else {
            return new JsonResponse(['error' => $this->translator->trans('invalid_type', [], 'api')], 422);
        }
        $conv->addMessage($message);
        $this->getDoctrine()->getManager()->persist($conv);
        $this->getDoctrine()->getManager()->flush();
        // Push message to the mercure hub to send it in real time
        $response = new MessageResponse();
        $response->id = $message->getId();
        $response->content = $message->getContent();
        $response->type = $message->getType();
        $response->sender_id = $this->security->getUser()->getId();
        $response->createdAt = $message->getCreatedAt()->format("yyyy-mm-dd HH:mm:ss");
        $update = new Update(
            sprintf('http://ghadhasymf.ga/api/conversations/%s/messages', $conv->getId()),
            $this->serializer->serialize($response , 'json')
        );
        $bus->dispatch($update);
        return new JsonResponse(null, 204);
    }

}