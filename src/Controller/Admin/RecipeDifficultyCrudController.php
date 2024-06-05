<?php

namespace App\Controller\Admin;

use App\Entity\Recipedifficulty;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipeDifficultyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipedifficulty::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            ->setPageTitle('index', 'Niveaux de difficulté')
            ->setPageTitle('new', 'Créer un niveau de difficulté')
            ->setPageTitle('edit', 'Modifier une niveau de difficulté')
            ->setPageTitle('detail', 'Niveaux de difficulté');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('difficultyname', 'Intitulé du niveau de difficulté'),
            
        ];
    }
    
}