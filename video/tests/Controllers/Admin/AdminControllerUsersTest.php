<?php

namespace App\Tests\Controllers\Admin;

use App\Entity\User;
use App\Tests\RoleAdmin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerUsersTest extends WebTestCase
{
    use RoleAdmin;

    private $client;
    private $entityManager;

    public function testUserDeleted(): void
    {
        $this->client->request('GET', '/admin/su/delete-user/4');
        $user = $this->entityManager->getRepository(User::class)->find(4);

        $this->assertNull($user);
    }
}
