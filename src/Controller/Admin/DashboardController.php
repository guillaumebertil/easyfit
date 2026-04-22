<?php

namespace App\Controller\Admin;

use App\Controller\Admin\UserCrudController;
use App\Controller\Admin\CategoryCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {        
        return $this->redirectToRoute('admin_user_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Easyfit');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fas fa-user');
        yield MenuItem::linkTo(CategoryCrudController::class, 'Categories', 'fas fa-folder');
        yield MenuItem::linkTo(ProductCrudController::class, 'Produits', 'fas fa-box');
        yield MenuItem::linkTo(ProductVariantCrudController::class, 'Déclinaisons', 'fas fa-boxes-stacked');
        yield MenuItem::linkTo(ProductImageCrudController::class, 'Images', 'fas fa-camera');
        yield MenuItem::linkTo(SizeCrudController::class, 'Tailles', 'fas fa-ruler');
        yield MenuItem::linkTo(ColorCrudController::class, 'Couleurs', 'fas fa-brush');
    }
}
