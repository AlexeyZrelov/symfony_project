<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
// use Symfony\Component\Panther\PantherTestCase;

// class DefaultControllerTest extends PantherTestCase
class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testSomething($url): void
    {
        $client = static::createClient();
        // $crawler = $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());

    }


    public function provideUrls(): array
    {
        return [
            ['/home'],
            ['/login']
        ];
    }

}
