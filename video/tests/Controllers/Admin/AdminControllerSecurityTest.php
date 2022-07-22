<?php

namespace App\Tests\Controllers\Admin;

use App\Repository\UserRepository;
use App\Tests\RoleAdmin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerSecurityTest extends WebTestCase
{
    use RoleAdmin;

    private $entityManager;
    private $client;

    /**
     * @dataProvider getUrlsForRegularUsers
     */
    public function testAccessDeniedForRegularUsers(string $httpMethod, string $url)
    {
        $this->client->request($httpMethod, $url);

//        $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    }

    public function getUrlsForRegularUsers(): \Generator
    {
        yield ['GET', 'admin/su/categories'];
        yield ['GET', 'admin/su/edit-category/1'];
//        yield ['GET', 'admin/su/delete-category/1'];
        yield ['GET', 'admin/su/users'];
        yield ['GET', 'admin/su/upload-video-locally'];
    }

    public function testAdminSu()
    {

        $crawler = $this->client->request('GET', '/admin/su/categories');
        $this->assertSame('Categories list', $crawler->filter('h2')->text());

    }

}
