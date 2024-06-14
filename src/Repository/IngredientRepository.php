<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

       /**
     * @return Ingredient[] 
        */
       public function ordered(): array
       {
        return $this->createQueryBuilder('i')
            ->orderBy('i.ingredientname', 'ASC')
            ->getQuery()
            ->getResult();
            ;
       }
}
