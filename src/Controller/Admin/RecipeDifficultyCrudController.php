<?php

namespace App\Controller\Admin;

use App\Entity\RecipeDifficulty;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipeDifficultyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecipeDifficulty::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Définir le titre pour la page 'index' (liste des entités)
            ->setPageTitle('index', 'Niveaux de difficulté')
            // Définir le titre pour la page 'new' (création d'une entité)
            ->setPageTitle('new', 'Créer un niveau de difficulté')
            // Définir le titre pour la page 'edit' (modification d'une entité)
            ->setPageTitle('edit', 'Modifier une niveau de difficulté')
            // Définir le titre pour la page 'detail' (détails d'une entité)
            ->setPageTitle('detail', 'Niveaux de difficulté');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('difficultyName', 'Intitulé du niveau de difficulté'),
            
        ];
    }
    
}