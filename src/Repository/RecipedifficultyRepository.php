<?php

namespace App\Repository;

use App\Entity\Recipedifficulty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Recipedifficulty>
 */
class RecipedifficultyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipedifficulty::class);
    }
}
