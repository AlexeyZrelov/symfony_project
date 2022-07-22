<?php

namespace App\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Twig\AppExtension;

class CategoryTest extends KernelTestCase
{
    protected $mockedCategoryTreeFrontPage;
    protected $mockedCategoryTreeAdminList;
    protected $mockedCategoryTreeAdminOptionList;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $urlgenerator = $kernel->getContainer()->get('router');
        $tested_classes = [
            'CategoryTreeAdminList',
            'CategoryTreeAdminOptionList',
            'CategoryTreeFrontPage'
        ];

        foreach ($tested_classes as $class) {

            $name = 'mocked'.$class;

            $this->$name = $this->getMockBuilder('App\Utils\\'.$class)
                ->disableOriginalConstructor()
                ->addMethods([$name]) // if no, all methods return null unless mocked
                ->getMock();

            $this->$name->urlGenerator = $urlgenerator;

        }

    }

    /**
     * @dataProvider dataForCategoryTreeAdminList
     */
    public function testCategoryTreeAdminList($string, $array)
    {
        $this->mockedCategoryTreeAdminList->categoriesArrayFromDb = $array;
        $array = $this->mockedCategoryTreeAdminList->buildTree();

        $this->assertSame($string, $this->mockedCategoryTreeAdminList->getCategoryList($array));
    }

    public function dataForCategoryTreeAdminList()
    {
        yield [
            '<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>Toys<a href="/admin/su/edit-category/2">Edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/su/delete-category/2">Delete</a></li></ul>',
            [['id'=>2, 'parent_id'=>null, 'name'=>'Toys']]
        ];
    }

    /**
     * @dataProvider dataForCategoryTreeAdminOptionList
     */
    public function testCategoryTreeAdminOptionList($arrayToCompare, $arrayFromDb)
    {
        $this->mockedCategoryTreeAdminOptionList->categoriesArrayFromDb = $arrayFromDb;
        $arrayFromDb = $this->mockedCategoryTreeAdminOptionList->buildTree();

        $this->assertSame($arrayToCompare, $this->mockedCategoryTreeAdminOptionList->getCategoryList($arrayFromDb));
    }

    public function dataForCategoryTreeAdminOptionList()
    {
        yield [
            [
                ['name'=>'Electronics', 'id'=>1],
                ['name'=>'--Computers', 'id'=>6],
                ['name'=>'----Laptops', 'id'=>8],
                ['name'=>'------HP', 'id'=>14]
            ],
            [
                ['name'=>'Electronics', 'id'=>1, 'parent_id'=>null],
                ['name'=>'Computers', 'id'=>6, 'parent_id'=>1],
                ['name'=>'Laptops', 'id'=>8, 'parent_id'=>6],
                ['name'=>'HP', 'id'=>14, 'parent_id'=>8]
            ]
        ];
    }

    /**
     * @dataProvider dataForCategoryTreeFrontPage
     */
    public function testCategoryTreeFrontPage($string, $array, $id)
    {
        $this->mockedCategoryTreeFrontPage->categoriesArrayFromDb = $array;
        $this->mockedCategoryTreeFrontPage->slugger = new AppExtension();
        $main_parent_id = $this->mockedCategoryTreeFrontPage->getMainParent($id)['id'];
        $array = $this->mockedCategoryTreeFrontPage->buildTree($main_parent_id);

        $this->assertSame($string, $this->mockedCategoryTreeFrontPage->getCategoryList($array));
    }

    public function dataForCategoryTreeFrontPage()
    {
        yield [
            '<ul><li><a href="/video-list/category/computers,6">Computers</a><ul><li><a href="/video-list/category/laptops,8">Laptops</a><ul><li><a href="/video-list/category/hp,14">HP</a></li></ul></li></ul></li></ul>' ,
            [
                ['name'=>'Electronics', 'id'=>1, 'parent_id'=>null],
                ['name'=>'Computers', 'id'=>6, 'parent_id'=>1],
                ['name'=>'Laptops', 'id'=>8, 'parent_id'=>6],
                ['name'=>'HP', 'id'=>14, 'parent_id'=>8]
            ],
            6
        ];
    }

}
