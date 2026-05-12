<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\ColorRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\SizeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/** Gère le panier d'achat : consultation, ajout d'article, suppression et mise à jour des quantités. */
final class CartController extends AbstractController
{
    /** Affiche le contenu du panier de l'utilisateur connecté. */
    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(CartRepository $cartRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer son panier
        $cart = $cartRepository->findOneBy(['user' => $user]);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * Ajoute un article au panier. Crée le panier s'il n'existe pas encore.
     * Si la variante est déjà présente dans le panier, incrémente la quantité plutôt que de créer un doublon.
     */
    #[Route('/cart/add', name: 'app_cart_add', methods: ['POST'])]
    public function add(
        Request $request,
        ProductRepository $productRepository,
        SizeRepository $sizeRepository,
        ColorRepository $colorRepository,
        ProductVariantRepository $productVariantRepository,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        EntityManagerInterface $entityManager): Response
    {
        // Récupérer les identifiants envoyés depuis le formulaire produit
        $productId = $request->request->get('product');
        $sizeId    = $request->request->get('size');
        $colorId   = $request->request->get('color');

        // Charger les entités correspondantes
        $product = $productRepository->find($productId);
        $size    = $sizeRepository->find($sizeId);
        $color   = $colorRepository->find($colorId);

        // Rechercher la variante correspondant à la combinaison produit / taille / couleur
        $productVariant = $productVariantRepository->findOneBy([
            'product' => $product,
            'size'    => $size,
            'color'   => $color
        ]);

        // Si la combinaison n'existe pas, afficher une erreur et revenir à la fiche produit
        if (!$productVariant) {
            $this->addFlash('error', 'Cette combinaison taille\couleur n\'est pas disponible');

            return $this->redirectToRoute('app_product_show', [
                'categorySlug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        }

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer ou créer le panier de l'utilisateur
        $cart = $cartRepository->findOneBy(['user' => $user]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);

            $entityManager->persist($cart);
            $entityManager->flush();
        }

        // Vérifier si la variante est déjà dans le panier
        $cartItem = $cartItemRepository->findOneBy(['productVariant' => $productVariant]);

        if ($cartItem) {
            // Incrémenter la quantité si l'article existe déjà
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            // Créer un nouvel article dans le panier
            $cartItem = new CartItem();
            $cartItem->setProductVariant($productVariant);
            $cartItem->setCart($cart);
            $cartItem->setQuantity(1);

            $entityManager->persist($cartItem);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }

    /** Supprime un article du panier. */
    #[Route('/cart/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(int $id, CartItemRepository $cartItemRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'article à supprimer
        $cartItem = $cartItemRepository->find($id);

        $entityManager->remove($cartItem);
        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Met à jour la quantité d'un article. Supprime automatiquement l'article si la quantité atteint zéro.
     */
    #[Route('/cart/update/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function update(int $id, Request $request, CartItemRepository $cartItemRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'article à mettre à jour
        $cartItem = $cartItemRepository->find($id);

        // Lire l'action demandée (increase ou decrease)
        $action = $request->request->get('action');

        if ($action === 'decrease') {
            $cartItem->setQuantity($cartItem->getQuantity() - 1);

            // Supprimer l'article si la quantité tombe à zéro
            if ($cartItem->getQuantity() <= 0) {
                $entityManager->remove($cartItem);
            }
        } else {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }
}
