<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\RecipeStep;
use App\Form\IngredientType;
use App\Entity\RecipeCategory;
use App\Entity\RecipeDifficulty;
use App\Entity\RecipeHasIngredient;
use App\Form\RecipeHasIngredientType;
use App\Form\RecipeStepsType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\CrudAutocompleteType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Définir le titre pour la page 'index' (liste des entités)
            ->setPageTitle('index', 'Recettes')
            // Définir le titre pour la page 'new' (création d'une entité)
            ->setPageTitle('new', 'Créer une nouvelle recette')
            // Définir le titre pour la page 'edit' (modification d'une entité)
            ->setPageTitle('edit', 'Modifier une recette')
            // Définir le titre pour la page 'detail' (détails d'une entité)
            ->setPageTitle('detail', 'Recettes');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            BooleanField::new('isActive', 'Active'),
            DateTimeField::new('createdAt', 'Créé le')->setFormat('dd/MM/YYYY HH:mm:ss')->hideOnForm(),
            AssociationField::new('recipeCategory', 'Catégorie de la recette')
            ->setFormTypeOptions([
                'class' => RecipeCategory::class,
                'choice_label' => 'categoryName',]),
            AssociationField::new('recipeDifficulty', 'Niveau de difficulté')
                ->setFormTypeOptions([
                    'class' => RecipeDifficulty::class,
                    'choice_label' => 'difficultyName',]),
            TextField::new('title', 'Titre de la recette'),
            TextEditorField::new('description', 'brève description de la recette'),
            TextField::new('photo', 'Image de la recette (png, jpg)'),
            TextField::new('duration', 'Temps de prépation de la recette'),
            CollectionField::new('ingredients', 'Ingrédients')
            ->setEntryType(RecipeHasIngredientType::class)
            ->setFormTypeOptions([
                'by_reference' => false,
            ]),
            
            CollectionField::new('recipeStep', 'Etapes de préparation de la recette')
            ->setEntryType(RecipeStepsType::class)
            ->setFormTypeOptions([
                'by_reference' => false,
            ])
      
        ];
}
}

