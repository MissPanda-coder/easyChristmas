<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RecipeIntroController extends AbstractController
{
    #[Route('/recipe/intro', name: 'recipe_intro')]
    #[IsGranted('ROLE_USER')]
    public function recipeIntro(): Response
    {
        return $this->render('recipe_intro/index.html.twig', [
            'page_title' => 'Recettes de Noël pour tous les goûts',
            'sectionName' => 'recipesIntro',
            'controller_name' => 'RecipeIntroController',
        ]);
    }
}
