<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Attribute\AsTwigFunction;

/** Extension Twig exposant la fonction getCategories() pour alimenter le menu de navigation sur toutes les pages. */
class AppExtension
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    #[AsTwigFunction('getCategories')]
    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }
}