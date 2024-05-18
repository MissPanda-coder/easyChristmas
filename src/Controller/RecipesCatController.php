<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipesCatController extends AbstractController
{
    #[Route('/recipes/cat', name: 'recipes_cat')]
    public function index(): Response
    {
        return $this->render('recipes_cat/index.html.twig', [
            'page_title' => 'Recettes de Noël pour tous les goûts',
            'sectionName' => 'recipesCat',
            'controller_name' => 'RecipesCatController',
        ]);
    }
}
