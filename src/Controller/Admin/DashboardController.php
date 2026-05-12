<?php

namespace App\Controller\Admin;

use App\Controller\Admin\UserCrudController;
use App\Controller\Admin\CategoryCrudController;
use App\Controller\Admin\ProductCrudController;
use App\Controller\Admin\ProductVariantCrudController;
use App\Controller\Admin\ProductImageCrudController;
use App\Controller\Admin\SizeCrudController;
use App\Controller\Admin\ColorCrudController;
use App\Controller\Admin\ReviewCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

/** Point d'entrée de l'administration EasyAdmin : configure le titre du panneau et le menu principal. */
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
        yield MenuItem::linkTo(ReviewCrudController::class, 'Avis', 'fas fa-star');
    }
}
