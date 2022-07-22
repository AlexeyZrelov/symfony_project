<?php

namespace App\Tests\Controllers\Admin;

use App\Entity\Category;
use App\Tests\RoleAdmin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class AdminControllerCategoriesTest extends WebTestCase
{

    use RoleAdmin;

    private $entityManager;
    private $client;

    public function testTextOnPage(): void
    {
        $crawler = $this->client->request('GET', '/admin/su/categories');
        $this->assertResponseIsSuccessful();
        $this->assertSame('Categories list', $crawler->filter('h2')->text());
        $this->assertStringContainsString('Electronics', $this->client->getResponse()->getContent());
    }

    public function testNumberOfItems()
    {
        $crawler = $this->client->request('GET', '/admin/su/categories');
        $this->assertCount(21, $crawler->filter('option'));
    }

    public function testNewCategory()
    {
        $crawler = $this->client->request('GET', '/admin/su/categories');
        $form = $crawler->selectButton('Add')->form([
            'category[parent]' => 1,
            'category[name]' => 'New Category3',
        ]);

        $this->client->submit($form);
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name'=>'New Category3']);

        $this->assertNotNull($category);
        $this->entityManager->rollback();
    }

    public function testEditCategory()
    {
        $crawler = $this->client->request('GET', '/admin/su/edit-category/1');
        $form = $crawler->selectButton('Save')->form([
            'category[parent]' => 0,
            'category[name]' => 'Electronics 2'
        ]);

        $this->client->submit($form);

        $category = $this->entityManager->getRepository(Category::class)->find(1);

        $this->assertSame('Electronics 2', $category->getName());

    }

    public function testDeleteCategory()
    {
        $crawler = $this->client->request('GET', '/admin/su/edit-category/1');
        $category = $this->entityManager->getRepository(Category::class)->find(1);

//        $this->assertNull($category);

    }


}
