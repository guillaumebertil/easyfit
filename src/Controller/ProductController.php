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

/** Affiche le catalogue par catégorie et les fiches produit, et permet aux utilisateurs connectés de soumettre des avis. */
final class ProductController extends AbstractController
{
    /** Affiche la liste des produits actifs d'une catégorie identifiée par son slug. */
    #[Route('/catalogue/{categorySlug}', name: 'app_product')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, string $categorySlug): Response
    {
        // Rechercher la catégorie par son slug
        $category = $categoryRepository->findOneBy(['slug' => $categorySlug]);

        // Retourner une erreur 404 si la catégorie n'existe pas
        if (!$category) {
            throw $this->createNotFoundException('Cette catégorie n\'existe pas');
        }

        // Récupérer uniquement les produits actifs de cette catégorie
        $products = $productRepository->findActiveByCategory($category);

        return $this->render('product/index.html.twig', [
            'title'    => 'Catalogue',
            'category' => $category,
            'products' => $products,
        ]);
    }

    /**
     * Affiche la fiche détaillée d'un produit et gère la soumission du formulaire d'avis.
     */
    #[Route('/catalogue/{categorySlug}/{slug}', name: 'app_product_show')]
    public function show(ProductRepository $productRepository, string $slug, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Rechercher le produit par son slug
        $product = $productRepository->findOneBy(['slug' => $slug]);

        // Retourner une erreur 404 si le produit n'existe pas
        if (!$product) {
            throw $this->createNotFoundException('Ce produit n\'existe pas');
        }

        // Créer un nouvel avis et le formulaire associé
        $review = new Review();
        $user = $this->getUser();
        $form = $this->createForm(ReviewType::class, $review);

        // Traiter la requête (lire les données POST soumises)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer l'avis à l'utilisateur connecté et au produit
            $review->setUser($user);
            $review->setProduct($product);
            $review->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($review);
            $entityManager->flush();

            // Rediriger vers la même fiche produit pour éviter la re-soumission du formulaire
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
