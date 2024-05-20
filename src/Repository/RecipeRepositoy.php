<?php

namespace App\Repository;

use App\Entity\RecipeCategory;
use Doctrine\ORM\EntityRepository;

class RecipeRepository extends EntityRepository
{

    function findByCategoryName(string $categoryName): array
    {
        return $this->createQueryBuilder('recipe')
            ->join(RecipeCategory::class,'cat', 'WITH', 'recipe.category = cat.id')
            ->where('cat.categoryName = :categoryName')
            ->setParameter('categoryName',$categoryName)
            ->getQuery()
            ->getResult();
    }
}