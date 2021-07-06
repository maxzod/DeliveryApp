<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\Offer;
use App\Entity\Order;
use App\Tests\Helpers\UserTokenTrait;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class OrderTest extends ApiTestCase
{
    use ReloadDatabaseTrait, UserTokenTrait;
    private Client $client;
    protected function setUp(): void
    {
        self::bootKernel();
        $this->client = self::createClient();
        $this->setHttpClient($this->client);
    }
    public function testClientCanGetHisOrders(): void
    {
        $token = $this->getClientToken($this->getContainer()->get(EntityManagerInterface::class));
        $response = $this->client->request('GET', '/api/orders', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            [
                'owner' => [
                    'email' => 'client@example.com'
                ]
            ]
        ]);
    }
    public function testDriverCanGetAvailableOrders(): void
    {
        $token = $this->getDriverToken($this->getContainer()->get(EntityManagerInterface::class));
        $response = $this->client->request('GET', '/api/orders', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            [
                'status' => Order::STATUS_WAITING
            ]
        ]);
    }
    public function testClientCanCreateOrders(): void
    {
        $token = $this->getClientToken($this->getContainer()->get(EntityManagerInterface::class));
        $response = $this->client->request('POST', '/api/orders', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' =>
                [
                  "note"=> "nothing",
                  "place"=> [
                    "name"=> "string",
                    "longitude"=> "string",
                    "latitude"=> "string",
                    "address" => "string"
                  ],
                  "drop_place" => [
                    "name"=> "string",
                    "longitude"=> "string",
                    "latitude"=> "string",
                    "address"=> "string"
                  ],
                  "products" => [
                    [
                     "name" => "string",
                      "quantity"=> 10,
                    ]
                  ],
                  "coupon"=> ""
                ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(204);
    }
    public function testDriverCantCreateOrders(): void
    {
        $token = $this->getDriverToken($this->getContainer()->get(EntityManagerInterface::class));
        $response = $this->client->request('POST', '/api/orders', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' =>
                [
                    "note"=> "nothing",
                    "place"=> [
                        "name"=> "string",
                        "longitude"=> "string",
                        "latitude"=> "string",
                        "address" => "string"
                    ],
                    "drop_place" => [
                        "name"=> "string",
                        "longitude"=> "string",
                        "latitude"=> "string",
                        "address"=> "string"
                    ],
                    "products" => [
                        [
                            "name" => "string",
                            "quantity"=> 10,
                        ]
                    ],
                    "coupon"=> ""
                ]
        ]);
        $this->assertResponseStatusCodeSame(403);
    }
    public function testDriverCanMakeOfferOnAvailableOrders()
    {
        $token = $this->getDriverToken($this->getContainer()->get(EntityManagerInterface::class));
        $order = $this->getContainer()->get(EntityManagerInterface::class)->getRepository(Order::class)->findOneBy(["status"=> 1]);
        $response = $this->client->request('POST', "/api/driver/orders/{$order->getId()}/offer", [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
                ],
                'json' => [
                    'price' => 50
                ]
            ]);
        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $this->getContainer()->get(EntityManagerInterface::class)->getRepository(Offer::class)->findAll());
    }
    public function testDriverCantMakeOfferOnProcessingOrders()
    {
        $token = $this->getDriverToken($this->getContainer()->get(EntityManagerInterface::class));
        $order = $this->getContainer()->get(EntityManagerInterface::class)->getRepository(Order::class)->findOneBy(["status"=> 2]);
        $response = $this->client->request('POST', "/api/driver/orders/{$order->getId()}/offer", [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'price' => 50
            ]
        ]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertCount(0, $this->getContainer()->get(EntityManagerInterface::class)->getRepository(Offer::class)->findAll());
    }
    public function testClientCanAcceptOffer()
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
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(204);
    }
    public function testClientCanTakeTheOrder()
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
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'id' => $order->getId(),
            'order_status' => Order::STATUS_DONE
        ]);
    }
}
