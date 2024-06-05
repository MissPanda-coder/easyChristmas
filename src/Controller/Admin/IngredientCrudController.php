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

            ->setPageTitle('index', 'Ingrédients')
            ->setPageTitle('new', 'Créer un nouvel ingrédient')
            ->setPageTitle('edit', 'Modifier l\'ingrédient')
            ->setPageTitle('detail', 'Détails de l\'ingrédient');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('ingredientName', 'Nom de l\'ingrédient'),
            
            
        ];
    }
    
}
