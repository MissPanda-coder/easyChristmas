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
        
            ->setPageTitle('index', 'Catégories')
            ->setPageTitle('new', 'Créer une nouvelle catégorie')
            ->setPageTitle('edit', 'Modifier une catégorie')
            ->setPageTitle('detail', 'Catégorie');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('categoryName', 'Nom de la catégorie'),
            
        ];
    }
    
}
