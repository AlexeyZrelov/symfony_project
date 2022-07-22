<?php

namespace App\Tests\Controllers\Front;

use App\Entity\Subscription;
use App\Tests\RoleUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerSubscriptionTest extends WebTestCase
{
    use RoleUser;

    private $client;
    private $entityManager;

    /**
     * @dataProvider urlsWithVideo
     */
    public function testLoggedInUserDoesNotSeeTextForNoMembers($url)
    {
        $this->client->request('GET', $url);

        $this->assertStringContainsString('Video for <b>MEMBERS</b> only.', $this->client->getResponse()->getBody()->getContent());

    }

    /**
     * @dataProvider urlsWithVideo
     */
    public function testNotLoggedInUserSeesTextForNoMembers($url)
    {
        $this->client->request('GET', $url);

        $this->assertStringContainsString('Video for <b>MEMBERS</b> only.', $this->client->getResponse()->getContent());
    }

    public function urlsWithVideo(): \Generator
    {
        yield ['/video-list/category/movies,4'];
        yield ['/search-results?query=movies'];
    }

    public function testExpiredSubscription()
    {
        $subscription = $this->entityManager
            ->getRepository(Subscription::class)
            ->find(2);

        $invalid_date = new \Datetime();
        $invalid_date->modify('-1 day');

        $this->entityManager->persist($subscription);
        $this->entityManager->flush();

        $this->client->request('GET', '/video-list/category/movies,4');

        $this->assertStringContainsString('Video for <b>MEMBERS</b> only.',
            $this->client->getResponse()->getContent());
    }

}
