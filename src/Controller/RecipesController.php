<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipesController extends AbstractController
{
    #[Route('/recipes', name: 'recipes')]
    public function index(): Response
    {
        return $this->render('recipes/index.html.twig', [
            'page_title' => 'Recettes de Noël pour tous les goûts',
            'controller_name' => 'RecipesController',
        ]);
    }
}
