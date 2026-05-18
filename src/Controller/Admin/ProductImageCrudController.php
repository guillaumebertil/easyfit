<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/** CRUD EasyAdmin pour la gestion des images associées aux produits. */
class ProductImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductImage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Image Produit')
            ->setEntityLabelInPlural('Images Produit');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('url')->setLabel('Image')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/img/catalogue')->setUploadDir('public/img/catalogue'),
            TextField::new('altText'),
            AssociationField::new('product'),
        ];
    }
}
