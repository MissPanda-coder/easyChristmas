<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\Recipecategory;
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
            ->addSelect('difficulty, stepnumber, description, quantity, unit, ingredient')
            ->join('recipe.recipedifficulty', 'difficulty')
            ->join('recipe.recipe_has_ingredient', 'quantity')
            ->join('recipe.recipe_has_ingredient', 'unit')
            ->join('recipe.recipe_has_ingredient', 'ingredient')
            ->join('recipe.recipestep', 'stepnumber')
            ->join('recipe.recipestep', 'description')
            ->where('recipe.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
