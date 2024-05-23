<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeIntroController extends AbstractController
{
    #[Route('/recipe/intro', name: 'recipe_intro')]
    public function index(): Response
    {
        return $this->render('recipe_intro/index.html.twig', [
            'page_title' => 'Recettes de Noël pour tous les goûts',
            'sectionName' => 'recipesIntro',
            'controller_name' => 'RecipeIntroController',
        ]);
    }
}
