<?php

namespace App\Controller\Admin;

use App\Entity\Recipecategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipeCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipecategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Définir le titre pour la page 'index' (liste des entités)
            ->setPageTitle('index', 'Catégories')
            // Définir le titre pour la page 'new' (création d'une entité)
            ->setPageTitle('new', 'Créer une nouvelle catégorie')
            // Définir le titre pour la page 'edit' (modification d'une entité)
            ->setPageTitle('edit', 'Modifier une catégorie')
            // Définir le titre pour la page 'detail' (détails d'une entité)
            ->setPageTitle('detail', 'Catégorie');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('categoryName', 'Nom de la catégorie'),
            
        ];
    }
    
}
