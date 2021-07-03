<?php


namespace App\Tests\Helpers;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\MediaObject;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;

trait UserTokenTrait
{
    private Client $client;

    public function setHttpClient(Client $client)
    {
        $this->client = $client;
    }

    private function getHttpClient()
    {
        return $this->client ?? HttpClient::create();
    }

    public function getClientToken(EntityManagerInterface $em)
    {
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'client@example.com']);
        $this->getHttpClient()->request('POST', '/api/login', [
            'json' => [
                'phone' => $user->getPhone()
            ]
        ]);
        $response = $this->getHttpClient()->request('POST', '/api/checkcode', [
            'json' => [
                'phone' => $user->getPhone(),
                'code' => 1111,
                'mobile_token' => "string"
            ]
        ]);
        $data = json_decode($response->getContent());
        return $data->token;
    }
    public function getDriverToken(EntityManagerInterface $em)
    {
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'driver@example.com']);
        $this->getHttpClient()->request('POST', '/api/login', [
            'json' => [
                'phone' => $user->getPhone()
            ]
        ]);
        $response = $this->getHttpClient()->request('POST', '/api/checkcode', [
            'json' => [
                'phone' => $user->getPhone(),
                'code' => 1111,
                'mobile_token' => "string"
            ]
        ]);
        $data = json_decode($response->getContent());
        return $data->token;
    }
}