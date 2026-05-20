<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

/** Gère l'inscription des nouveaux utilisateurs via le formulaire RegisterUserType. */
final class RegisterController extends AbstractController
{
    /**
     * Affiche le formulaire d'inscription et crée le compte utilisateur s'il est valide.
     */
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Créer un nouvel utilisateur et le formulaire associé
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);

        // Traiter la requête (lire les données POST soumises)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le nouvel utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Connecter l'utilisateur automatiquement après l'inscription
            $security->login($user, 'form_login', 'main');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('register/index.html.twig', [
            'title' => 'Inscription',
            'registerForm' => $form,
        ]);
    }
}
