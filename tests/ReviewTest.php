<?php


namespace App\Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\Offer;
use App\Entity\Order;
use App\Tests\Helpers\UserTokenTrait;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ReviewTest extends ApiTestCase
{
    use ReloadDatabaseTrait, UserTokenTrait;
    private Client $client;
    protected function setUp(): void
    {
        self::bootKernel();
        $this->client = self::createClient();
        $this->setHttpClient($this->client);
    }

    public function testClientCanReviewDriver()
    {
        $driver = $this->getDriverToken($this->getContainer()->get(EntityManagerInterface::class));
        $order = $this->getContainer()->get(EntityManagerInterface::class)->getRepository(Order::class)->findOneBy(["status"=> 1]);
        $this->client->request('POST', "/api/driver/orders/{$order->getId()}/offer", [
            'headers' => [
                'Authorization' => 'Bearer '.$driver,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'price' => 50
            ]
        ]);
        $token = $this->getClientToken($this->getContainer()->get(EntityManagerInterface::class));
        $offer = $this->getContainer()->get(EntityManagerInterface::class)->getRepository(Offer::class)->findOneBy(["theOrder"=> $order->getId()]);
        $this->client->request('GET', "/api/offer/{$offer->getId()}/accept", [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
        ]]);
        $this->client->request('GET', "/api/client/orders/{$order->getId()}/take_order", [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
        ]]);
        $this->client->request('POST', "/api/orders/{$order->getId()}/review", [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
        ],
        'json' => [
            'stars' => 5,
            'comment' => 'good'
        ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testDriverCanReviewClient()
    {
        $driver = $this->getDriverToken($this->getContainer()->get(EntityManagerInterface::class));
        $order = $this->getContainer()->get(EntityManagerInterface::class)->getRepository(Order::class)->findOneBy(["status"=> 1]);
        $this->client->request('POST', "/api/driver/orders/{$order->getId()}/offer", [
            'headers' => [
                'Authorization' => 'Bearer '.$driver,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'price' => 50
            ]
        ]);
        $token = $this->getClientToken($this->getContainer()->get(EntityManagerInterface::class));
        $offer = $this->getContainer()->get(EntityManagerInterface::class)->getRepository(Offer::class)->findOneBy(["theOrder"=> $order->getId()]);
        $this->client->request('GET', "/api/offer/{$offer->getId()}/accept", [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]]);
        $this->client->request('GET', "/api/client/orders/{$order->getId()}/take_order", [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]]);
        $this->client->request('POST', "/api/orders/{$order->getId()}/review", [
            'headers' => [
                'Authorization' => 'Bearer '.$driver,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'stars' => 5,
                'comment' => 'good'
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }
}