<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RecipeDetailedController extends AbstractController
{
    #[Route('/recipe/detailed', name: 'recipe_detailed')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('recipe_detailed/index.html.twig', [
            'page_title' => 'Recettes de Noël pour tous les goûtts',
            'sectionName' => 'recipeDetailed',
            'controller_name' => 'RecipeDetailedController',
        ]);
    }

    
}
