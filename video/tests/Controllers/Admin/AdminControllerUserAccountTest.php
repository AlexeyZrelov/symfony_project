<?php

namespace App\Tests\Controllers\Admin;

use App\Entity\User;
use App\Tests\RoleUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerUserAccountTest extends WebTestCase
{

    use RoleUser;

    private $client;
    private $entityManager;

    public function testUserDeleteAccount()
    {

        $crawler = $this->client->request('GET', '/admin/delete-account');
        $user = $this->entityManager->getRepository(User::class)->find(3);

        $this->assertNull($user);
    }

    public function testUserChangePassword()
    {

        $crawler = $this->client->request('GET', '/admin/');
        $form = $crawler->seletButton('Save')->form([
            'user[name]' => 'name',
            'user[last_name]' => 'last name',
            'user[email]' => 'email@email.email',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password'
        ]);

        $this->client->submit($form);

        $user = $this->entityManager->getRepository(User::class)->find(3);

        $this->assertSame('name', $user->getName());

    }

}
