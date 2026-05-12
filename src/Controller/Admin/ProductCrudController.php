<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/** CRUD EasyAdmin pour la gestion des produits (nom, prix, catégorie, drapeaux isFeatured et isActive). */
class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            AssociationField::new('category'),
            NumberField::new('price'),
            AssociationField::new('size'),
            AssociationField::new('color'),
            TextEditorField::new('description')->hideOnIndex(),
            BooleanField::new('isFeatured'),
            BooleanField::new('isActive'),
        ];
    }
}
