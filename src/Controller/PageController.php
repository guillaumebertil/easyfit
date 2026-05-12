<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/** Affiche les pages statiques du site : mentions légales, CGV et contact. */
final class PageController extends AbstractController
{
    /** Affiche la page des mentions légales. */
    #[Route('/mentions-legales', name: 'app_legal_notices')]
    public function legals(): Response
    {
        return $this->render('page/legal_notices.html.twig', [
            'title' => 'Mentions légales',
            'controller_name' => 'PageController',
        ]);
    }

    /** Affiche la page des conditions générales de vente. */
    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('page/cgv.html.twig', [
            'title' => 'CGV',
            'controller_name' => 'PageController',
        ]);
    }

    /** Affiche la page de contact. */
    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', [
            'title' => 'Contact',
            'controller_name' => 'PageController',
        ]);
    }
}
