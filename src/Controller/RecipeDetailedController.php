<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeDetailedController extends AbstractController
{
    #[Route('/recipe/detailed', name: 'app_recipe_detailed')]
    public function index(): Response
    {
        return $this->render('recipe_detailed/index.html.twig', [
            'controller_name' => 'RecipeDetailedController',
        ]);
    }
}
