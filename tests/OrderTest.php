<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
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
}
