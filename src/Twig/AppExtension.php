<?php

namespace App\Twig;

use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Attribute\AsTwigFunction;

/** Extension Twig exposant des fonctions globales: navigation par catégories et compteur panier. */
class AppExtension
{
    private CategoryRepository $categoryRepository;
    private CartRepository $cartRepository;
    private Security $security;

    public function __construct(CategoryRepository $categoryRepository, CartRepository $cartRepository, Security $security)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cartRepository     = $cartRepository;
        $this->security           = $security;
    }

    /** Retourne toutes les catégories pour alimenter le menu de navigation. */
    #[AsTwigFunction('getCategories')]
    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    /** Retourne le nombre d'articles dans le panier de l'utilisateur connecté, 0 s'il n'est pas connecté */
    #[AsTwigFunction('getCartCount')]
    public function getCartCount(): int
    {
        // Récupérer l'utilisateur
        $user = $this->security->getUser();
        if (!$user) {
            return 0;
        }

        // Récupérer le panier de l'utilisateur
        $cart = $this->cartRepository->findOneBy(['user' => $user]);
        if (!$cart) {
            return 0;
        }

        // Récupérer les items du panier
        $cartItems = $cart->getCartItems();

        $totalQuantity = 0;

        foreach ($cartItems as $cartItem) {
            $totalQuantity += $cartItem->getQuantity();
        }

        // Retourner la quantité totale des articles du panier
        return $totalQuantity;
    }
}