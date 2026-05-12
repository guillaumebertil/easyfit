<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/catalogue/{categorySlug}', name: 'app_product')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, string $categorySlug): Response
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
            'title'    => 'Catalogue',
            'category' => $category,
            'products' => $products,
        ]);
    }

    #[Route('/catalogue/{categorySlug}/{slug}', name: 'app_product_show')]
    public function show(ProductRepository $productRepository, string $slug, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Ce produit n\'existe pas');
        }

        $review = new Review();
        $user = $this->getUser();
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($user);
            $review->setProduct($product);
            $review->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_show', [
                'categorySlug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        }

        return $this->render('product/productDetails.html.twig', [
            'title'   => $product,
            'product' => $product,
            'form'    => $form,
        ]);
    }
}
