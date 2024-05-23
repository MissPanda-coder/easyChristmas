<?php

namespace App\Controller\Admin;

use App\Entity\Unit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UnitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Unit::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Définir le titre pour la page 'index' (liste des entités)
            ->setPageTitle('index', 'Unités de mesure')
            // Définir le titre pour la page 'new' (création d'une entité)
            ->setPageTitle('new', 'Créer une nouvelle uniité de mesure')
            // Définir le titre pour la page 'edit' (modification d'une entité)
            ->setPageTitle('edit', 'Modifier une unité de mesure')
            // Définir le titre pour la page 'detail' (détails d'une entité)
            ->setPageTitle('detail', 'Unités de mesure');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('unitName', 'Intitulé de l\'unité de mesure'),
            
        ];
    }
    
    

}
