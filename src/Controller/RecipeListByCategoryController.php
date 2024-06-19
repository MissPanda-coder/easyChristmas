<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeListByCategoryController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    private RecipeRepository $recipeRepository;
    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->recipeRepository = $em->getRepository(Recipe::class);
    }
    #[Route('/recipe/list/by/category/{categoryname}', name: 'recipe_list_by_category')]
    #[IsGranted('ROLE_USER')]
    public function recipesByCategories(string $categoryname): Response
    {
        $recipes = $this->recipeRepository->findByCategoryname($categoryname);

        return $this->render('recipe_list_by_category/index.html.twig', [
            'recipes' => $recipes,
            'page_title' => 'Recettes de Noël pour tous les goûts',
            'sectionName' => 'recipesListByCategory',
            'controller_name' => 'RecipeListByCategoryController',
        ]);
    }
}
