<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Entity\RecipeDifficulty;
use App\Entity\RecipeHasIngredient;
use App\Entity\Unit;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipesController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    private RecipeRepository $recipeRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->recipeRepository = $em->getRepository(Recipe::class);
    }

    #[Route('/recipes/{categoryName}', name: 'recipes')]
    public function index(string $categoryName): Response
    {
        /*
        $category = new RecipeCategory();
        $category->setCategoryName("dessert");

        $difficulty = new RecipeDifficulty();
        $difficulty->setDifficultyName("Facile");

        $recipe1 = new Recipe();
        $recipe1->setTitle("Recette 3");
        $recipe1->setDescription("Ma première recipe");
        $recipe1->setCategory($category);
        $recipe1->setDifficulty($difficulty);
        $recipe1->setDuration(10);

        $recipe2 = new Recipe();
        $recipe2->setTitle("Recette 4");
        $recipe2->setDescription("Ma seconde recipe");
        $recipe2->setCategory($category);
        $recipe2->setDifficulty($difficulty);
        $recipe2->setDuration(20);

        $this->entityManager->persist($recipe1);
        $this->entityManager->persist($recipe2);
        $this->entityManager->flush();
        */

        $recipes = $this->recipeRepository->findByCategoryName($categoryName);

        return $this->render('recipes/index.html.twig', [
            'recipes' => $recipes,
            'page_title' => 'Recettes de Noël pour tous les goûts',
            'sectionName' => 'recipes',
            'controller_name' => 'RecipesController',
        ]);
    }
}
