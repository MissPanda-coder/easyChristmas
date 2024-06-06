<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeDetailedController extends AbstractController
{

    #[Route('/recipe/{slug}-{id}', name: 'recipe_detailed')]
    #[IsGranted('ROLE_USER')]
    public function recipeDetailed(Request $request, string $slug, int $id, RecipeRepository $recipe): Response
    {
        $recipe = $recipe->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('La recette n\'existe pas');
        }

        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe_detailed', [
                'slug' => $recipe->getSlug(),
                'id' => $recipe->getId()
            ]);
        }
        
        return $this->render('recipe_detailed/index.html.twig', [
            'recipe' => $recipe,
            'page_title' => 'Recettes de Noël pour tous les goûts',
            'sectionName' => 'recipeDetailed',
            'controller_name' => 'RecipeDetailedController',
        ]);
    }

    
}
