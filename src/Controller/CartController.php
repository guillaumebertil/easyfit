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

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(CartRepository $cartRepository): Response
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['user' => $user]);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

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
        $productId = $request->request->get('product');
        $sizeId    = $request->request->get('size');
        $colorId   = $request->request->get('color');

        $product = $productRepository->find($productId);
        $size    = $sizeRepository->find($sizeId);
        $color   = $colorRepository->find($colorId);

        $productVariant = $productVariantRepository->findOneBy([
            'product' => $product,
            'size'    => $size,
            'color'   => $color
        ]);

        if (!$productVariant) {
            $this->addFlash('error', 'Cette combinaison taille\couleur n\'est pas disponible');

            return $this->redirectToRoute('app_product_show', [
                'categorySlug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        }

        $user = $this->getUser();

        $cart = $cartRepository->findOneBy(['user' => $user]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);

            $entityManager->persist($cart);
            $entityManager->flush();
        }

        $cartItem = $cartItemRepository->findOneBy(['productVariant' => $productVariant]);

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            $cartItem = new CartItem();
            $cartItem->setProductVariant($productVariant);
            $cartItem->setCart($cart);
            $cartItem->setQuantity(1);

            $entityManager->persist($cartItem);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(int $id,CartItemRepository $cartItemRepository, EntityManagerInterface $entityManager): Response
    {
        $cartItem = $cartItemRepository->find($id);

        $entityManager->remove($cartItem);
        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/update/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function update(int $id, Request $request, CartItemRepository $cartItemRepository, EntityManagerInterface $entityManager): Response
    {
        $cartItem =$cartItemRepository->find($id);

        $action = $request->request->get('action');

        if ($action === 'decrease') {
            $cartItem->setQuantity($cartItem->getQuantity() - 1);

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
