<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/** Transforme le panier en commande et affiche la page de confirmation. */
final class OrderController extends AbstractController
{
    #[Route('/order/create', name: 'app_order_create', methods: ['POST'])]
    public function create(CartRepository $cartRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer son panier
        $cart = $cartRepository->findByUser($user);

        // Créer une nouvelle Order
        $order = new Order();
        $order->setUser($user);
        $order->setCreatedAt(new \DateTimeImmutable('now'));

        // Calculer le total de la commande
        $total = 0;
        foreach ($cart->getCartItems() as $cartItem) {
            $total += $cartItem->getProductVariant()->getProduct()->getPrice() * $cartItem->getQuantity();
        }
        $order->setTotal($total);

        // Passer le statut à "pending"
        $order->setStatus(OrderStatus::PENDING);

        $entityManager->persist($order);

        // Créer un OrderItem pour chaque CartItem
        foreach ($cart->getCartItems() as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setCustomerOrder($order);
            $orderItem->setProductVariant($cartItem->getProductVariant());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setPrice($cartItem->getProductVariant()->getProduct()->getPrice());

            $entityManager->persist($orderItem);
            $entityManager->remove($cartItem);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_order_confirm');
    }

    #[Route('/order/confirm', name: 'app_order_confirm')]
    public function confirm(): Response
    {
        return $this->render('order/confirm.html.twig');
    }
}
