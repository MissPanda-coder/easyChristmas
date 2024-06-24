<?php

namespace App\Controller;

use App\Entity\Recipe;
use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use App\Form\UserRecipeType;
use App\Repository\UnitRepository;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UserCreateRecipeController extends AbstractController
{
    #[Route('/api/user/recipe/create', name: 'user_recipe_create', methods: ['POST'])]
    public function userCreateRecipe(Request $request, EntityManagerInterface $em, IngredientRepository $ingredientRepository, UnitRepository $unitRepository, MailerInterface $mailer): Response
    {
        // Ingrédients et unités triés par ordre alphabétique
        $ingredients = $ingredientRepository->ordered();
        $units = $unitRepository->orderedUnits();

        // Formulaire de création de recette
        $recipe = new Recipe();
        $recipeForm = $this->createForm(UserRecipeType::class, $recipe, [
            'ingredients' => $ingredients,
            'units' => $units,
        ]);
        $recipeForm->handleRequest($request);

        if ($recipeForm->isSubmitted() && $recipeForm->isValid()) {
            $recipe->setUser($this->getUser());
            $recipe->setCreatedAt(new DateTimeImmutable());

            // Modification du nom de la photo pour éviter les conflits de noms
            $photoFile = $recipeForm->get('photo')->getData();
            if ($photoFile) {
                $ext = $photoFile->guessExtension();
                $filename = bin2hex(random_bytes(10)) . '.' . $ext;

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // gérer l'exception si quelque chose se passe mal pendant le téléchargement
                }

                $recipe->setPhoto($filename);
            }

            // Génération du slug pour l'URL
            $slugify = new Slugify();
            $slug = $slugify->slugify($recipe->getTitle());
            $recipe->setSlug($slug);

            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Recette créée avec succès !');

            // Envoi d'un e-mail de notification à l'administrateur
            $user = $this->getUser();
            $email = (new TemplatedEmail())
            ->from(new Address('no-reply@easychristmas.fr', 'Easy Christmas'))
            ->to('admin@easychristmas.fr')
            ->subject('Nouvelle recette à activer')
            ->htmlTemplate('user_create_recipe/emailActivation.html.twig')
            ->context([
                'user' => $user,
                'recipe' => $recipe,
                     ]);
 
                 $mailer->send($email);

            return $this->redirectToRoute('profile');
        }

        return $this->render('user_create_recipe/index.html.twig', [
            'recipeForm' => $recipeForm->createView(),
            'page_title' => 'Proposer une recette',
            'sectionName' => 'userRecipe',
            'content' => 'content',
            'controller_name' => 'UserCreateRecipeController',
        ]);
    }
}
