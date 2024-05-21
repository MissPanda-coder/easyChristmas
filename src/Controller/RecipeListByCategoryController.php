<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeListByCategoryController extends AbstractController
{
    #[Route('/recipe/list/by/category', name: 'recipe_list_by_category')]
    public function index(): Response
    {
        return $this->render('recipe_list_by_category/index.html.twig', [
            'page_title' => 'Recettes de Noël pour tous les goûts',
            'sectionName' => 'recipes',
            'controller_name' => 'RecipeListByCategoryController',
        ]);
    }
}
