<?php

namespace App\Controller\Admin;

use App\Entity\ProductVariant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

/** CRUD EasyAdmin pour les déclinaisons produit (combinaison produit + taille + couleur avec niveau de stock). */
class ProductVariantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductVariant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('product'),
            AssociationField::new('size'),
            AssociationField::new('color'),
            IntegerField::new('stock'),
        ];
    }
}
