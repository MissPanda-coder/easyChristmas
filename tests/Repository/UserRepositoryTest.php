<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{

    private EntityManager $entityManager;

    private UserRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->repository = $this->entityManager
            ->getRepository(User::class);
    }

    public function testFindUserByEmail(): void
    {
        $usersFound = $this->repository->findByEmail("j.doe@company.com");

        // check first time without added a user, 0 user shall be returned
        $this->assertCount(0, $usersFound);

        // Adding a user
        $newUser = new User();
        $newUser->setEmail("j.doe@company.com")
            ->setPassword("toto")
            ->setRoles(["big_boss"]);

        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        // check second time after added a user, 1 user shall be returned
        $usersFound = $this->repository->findByEmail("j.doe@company.com");

        $this->assertCount(1, $usersFound);
        $this->assertEquals("j.doe@company.com", $usersFound[0]->getEmail());
    }
}
