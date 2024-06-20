<?php

namespace App\Repository;

use App\Entity\Recipecategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Recipecategory>
 */
class RecipecategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeCategory::class);
    }

    /**
     * @param string $categoryname
     * @return Recipe[]
     */
    public function findByCategoryname(string $categoryname): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.recipecategory', 'c')
            ->where('c.categoryname = :categoryname')
            ->setParameter('categoryname', $categoryname)
            ->getQuery()
            ->getResult();
    }
}
