<?php

namespace App\Controller;

use App\Form\EditProfileType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_account');
        }
        
        return $this->render('account/index.html.twig', [
            'title' => 'Compte',
            'user' => $user,
            'editForm' => $form,
        ]);
    }

    #[Route('/account/history', name: 'app_account_history')]
    public function orders(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        $orders = $orderRepository->findBy(['user' => $user]);

        return $this->render('account/history.html.twig', [
            'title' => 'Historiques des commandes',
            'orders' => $orders,
        ]);
    }
}
