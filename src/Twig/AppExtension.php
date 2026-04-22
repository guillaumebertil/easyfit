<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Attribute\AsTwigFunction;

class AppExtension
{
    private $categoryRepository;

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