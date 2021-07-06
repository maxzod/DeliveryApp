<?php


namespace App\Messages;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NotificationHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private HttpClientInterface $client;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->client = $client;
    }

    public function __invoke(Notification $notification)
    {
        /**
         * @var User $user
         */
        $user = $this->entityManager->getRepository(User::class)->find($notification->getUserId());
        $noti = new \App\Entity\Notification();
        $noti->setTitle($notification->getTitle());
        $noti->setContent($notification->getContent());
        $noti->setOwner($user);
        $this->entityManager->persist($noti);
        $this->entityManager->flush();

        $API_ACCESS_KEY = "AAAANxkZC9Q:APA91bGZbPxVtIANrLR9Jm-FezIvpFkzcHr9BUtSV5AFoJPXYzU5cFJxBOCFZE_zjJ0CvnIqy827hOnbBqLbSiTTm9dLMPf-x3JrVqA-9J44KZpwI4ja66ToRtAyMcW8C37npY-6FcNs";
        $url = 'https://fcm.googleapis.com/fcm/send';
        $msg = [
            'body'=> $notification->getContent(),
            'title'=> $notification->getTitle()
        ];
        $data = [
            'body'   => $notification->getInputs(),
            'title'     => $notification->getTitle(),
            'message' => true,
        ];
        $fields = [
            'to'=> $user->getMobileToken(),
            'notification'=> $msg,
            'data'=> $data,
            'priority'=> 'high'
        ];
        $headers = [
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
        ];
        $result = $this->client->request(
            'POST',
            $url,
            [
                'headers' => $headers,
                'body' => json_encode($fields)
            ]
        );
        try {
            $this->logger->alert("curl_result:" . $result->getContent());
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            $this->logger->alert("curl_result:" . $e->getMessage());
        }
    }
}