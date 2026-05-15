<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/** Affiche la page d'accueil avec les produits mis en avant et les sections femmes/hommes. */
final class HomeController extends AbstractController
{
    /**
     * Charge les produits mis en avant et les produits par genre pour la page d'accueil.
     */
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        // Récupérer les catégories femmes et hommes
        $womenCategory    = $categoryRepository->findOneBy(['name' => 'femmes']);
        $menCategory      = $categoryRepository->findOneBy(['name' => 'hommes']);

        // Récupérer les produits mis en avant (isFeatured = true)
        $featuredProducts = $productRepository->findFeatured();

        // Récupérer les produits actifs pour chaque genre
        $womenProducts    = $productRepository->findActiveByCategory($womenCategory);
        $menProducts      = $productRepository->findActiveByCategory($menCategory);

        return $this->render('home/index.html.twig', [
            'title' => 'Vêtements de sport',
            'featuredProducts' => $featuredProducts,
            'womenCategory'    => $womenCategory,
            'menCategory'      => $menCategory,
            'womenProducts'    => $womenProducts,
            'menProducts'      => $menProducts,
        ]);
    }
}
