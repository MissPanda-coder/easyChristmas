<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\Recipecategory;
use App\Entity\Recipedifficulty;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }


    function findByCategoryname(string $categoryname): array
    {
        return $this->createQueryBuilder('recipe')
            ->join(Recipecategory::class,'category', 'WITH', 'recipe.recipecategory = category.id')
            ->where('category.categoryname = :categoryname')
            ->setParameter('categoryname',$categoryname)
            ->getQuery()
            ->getResult();
    }


    function findById(int $id): array
{
        return $this->createQueryBuilder('recipe')
            ->addSelect('difficulty')
            ->join('recipe.recipedifficulty', 'difficulty')
            ->where('recipe.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
}

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            
    //      
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
