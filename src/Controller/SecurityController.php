<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/** Gère l'authentification Symfony : affichage du formulaire de connexion et déconnexion. */
class SecurityController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion avec la dernière erreur d'authentification et le dernier identifiant saisi.
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupérer le dernier identifiant saisi par l'utilisateur pour pré-remplir le champ
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'title' => 'Connexion',
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Point d'entrée de la déconnexion — la logique est entièrement gérée par le pare-feu Symfony.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode ne sera jamais exécutée : Symfony intercepte la route /logout
        // via la configuration du pare-feu dans security.yaml avant d'atteindre ce code.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
