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
        parent::__construct($registry, Recipecategory::class);
    }
}
