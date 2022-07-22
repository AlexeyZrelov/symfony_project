<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
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
        yield Field::new('url');
        yield Field::new('price');
        yield Field::new('priceCurrency');
        yield AssociationField::new('product');

    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
//            ->setEntityLabelInSingular('...')
//            ->setDateFormat('...')
            ->setPageTitle('index', 'Offers list')
            // ...
            ;
    }
}
