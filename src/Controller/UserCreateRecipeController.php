<?php

namespace App\Controller;

use App\Entity\Recipe;
use DateTimeImmutable;
use App\Form\UserRecipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserCreateRecipeController extends AbstractController
{
    #[Route('/user/create/recipe', name: 'user_create_recipe')]
    public function userCreateRecipe(Request $request, EntityManagerInterface $em): Response
    {


         // Gestion du formulaire de création de recette
         $recipe = new Recipe();
         $recipeForm = $this->createForm(UserRecipeType::class, $recipe);
         $recipeForm->handleRequest($request);
 
         if ($recipeForm->isSubmitted() && $recipeForm->isValid()) {
             $recipe->setUser($this->getUser());
             $recipe->setCreatedAt(new DateTimeImmutable());
             $em->persist($recipe);
             $em->flush();
             $this->addFlash('success', 'Recette créée avec succès !');
 
             return $this->redirectToRoute('profile');
         }
 
    
        return $this->render('user_create_recipe/index.html.twig', [
            'recipeForm' => $recipeForm->createView(),
             'page_title' => 'Proposer une recette',
             'sectionName' => 'userRecipe',
            'controller_name' => 'UserCreateRecipeController',
        ]);
    }
}
