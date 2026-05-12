<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/** CRUD EasyAdmin pour la gestion des utilisateurs et de leurs rôles (ROLE_ADMIN / ROLE_USER). */
class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstname'),
            TextField::new('lastname'),
            EmailField::new('email'),
            ChoiceField::new('roles')->setChoices([
                'Administrateur' => 'ROLE_ADMIN',
                'Utilisateurs'   => 'ROLE_USER',
            ])->allowMultipleChoices()->hideOnIndex(),
        ];
    }
}
