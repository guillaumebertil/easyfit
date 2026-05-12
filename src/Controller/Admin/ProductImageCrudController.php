<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

/** CRUD EasyAdmin pour la gestion des images associées aux produits. */
class ProductImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            UrlField::new('url'),
            TextField::new('altText'),
            AssociationField::new('product'),
        ];
    }
}
