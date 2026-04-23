<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/catalogue/{categorySlug}', name: 'app_product')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, $categorySlug): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $categorySlug]);

        if (!$category) {
            throw $this->createNotFoundException('Cette catégorie n\'existe pas');
        }

        $products = $productRepository->findBy([
                'isActive' => true,
                'category' => $category,
            ]);

        return $this->render('product/index.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    #[Route('/catalogue/{categorySlug}/{slug}', name: 'app_product_show')]
    public function show(ProductRepository $productRepository, $slug): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Ce produit n\'existe pas');
        }

        return $this->render('product/productDetails.html.twig', [
            'product' => $product,
        ]);
    }
}
