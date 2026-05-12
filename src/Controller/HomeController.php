<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $womenCategory    = $categoryRepository->findOneBy(['name' => 'femmes']);
        $menCategory      = $categoryRepository->findOneBy(['name' => 'hommes']);

        $featuredProducts = $productRepository->findBy(['isFeatured' => true]);
        $womenProducts    = $productRepository->findBy(['category' => $womenCategory, 'isActive' => true]);
        $menProducts      = $productRepository->findBy(['category' => $menCategory, 'isActive' => true]);

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
