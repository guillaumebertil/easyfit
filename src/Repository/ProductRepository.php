<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Retourne les produits actifs d'une catégorie
     * 
     * @return Product[]
     */
    public function findActiveByCategory(Category $category): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :category')
            ->andWhere('p.isActive = true')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les produits mis en avant
     * 
     * @return Product[]
     */
    public function findFeatured(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isFeatured = true')
            ->getQuery()
            ->getResult();
    }
}
