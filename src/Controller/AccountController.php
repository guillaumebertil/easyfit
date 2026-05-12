<?php

namespace App\Controller;

use App\Form\EditProfileType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/** Gère les pages du compte client : profil, modification des informations et historique des commandes. */
final class AccountController extends AbstractController
{
    /** Affiche la page de profil de l'utilisateur connecté. */
    #[Route('/account', name: 'app_account')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        return $this->render('account/index.html.twig', [
            'title' => 'Mon compte',
            'user' => $user,
        ]);
    }

    /**
     * Affiche le formulaire de modification du profil et enregistre les changements s'il est valide.
     */
    #[Route('/account/edit', name: 'app_account_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Créer le formulaire pré-rempli avec les données de l'utilisateur
        $form = $this->createForm(EditProfileType::class, $user);

        // Traiter la requête (lire les données POST soumises)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications en base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit_profil.html.twig', [
            'title' => 'Compte',
            'user' => $user,
            'editForm' => $form,
        ]);
    }

    /** Affiche l'historique des commandes de l'utilisateur connecté. */
    #[Route('/account/history', name: 'app_account_history')]
    public function orders(OrderRepository $orderRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer toutes ses commandes
        $orders = $orderRepository->findBy(['user' => $user]);

        return $this->render('account/history.html.twig', [
            'title' => 'Historiques des commandes',
            'orders' => $orders,
        ]);
    }
}
