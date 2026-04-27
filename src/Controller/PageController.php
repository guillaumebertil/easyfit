<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_legal_notices')]
    public function legals(): Response
    {
        return $this->render('page/legal_notices.html.twig', [
            'title' => 'Mentions légales',
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('page/cgv.html.twig', [
            'title' => 'CGV',
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', [
            'title' => 'Contact',
            'controller_name' => 'PageController',
        ]);
    }
}
