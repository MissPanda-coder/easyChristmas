<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ingredient::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Définir le titre pour la page 'index' (liste des entités)
            ->setPageTitle('index', 'Ingrédients')
            // Définir le titre pour la page 'new' (création d'une entité)
            ->setPageTitle('new', 'Créer un nouvel ingrédient')
            // Définir le titre pour la page 'edit' (modification d'une entité)
            ->setPageTitle('edit', 'Modifier l\'ingrédient')
            // Définir le titre pour la page 'detail' (détails d'une entité)
            ->setPageTitle('detail', 'Détails de l\'ingrédient');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('ingredientName', 'Nom de l\'ingrédient'),
            
            
        ];
    }
    
}
