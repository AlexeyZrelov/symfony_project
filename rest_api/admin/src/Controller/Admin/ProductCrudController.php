<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    public function configureFields(string $pageName): iterable
    {
        yield Field::new('name');
        yield Field::new('description');
        yield ImageField::new('image')
            ->setBasePath('uploads/images/products')
            ->setUploadDir('public/uploads/images/products')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
        ;

        yield CollectionField::new('offers');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
//            ->setEntityLabelInSingular('...')
//            ->setDateFormat('...')
            ->setPageTitle('index', 'Products list')
            // ...
            ;
    }
}
