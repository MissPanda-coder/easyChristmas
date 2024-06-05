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
        
            ->setPageTitle('index', 'Unités de mesure')
            ->setPageTitle('new', 'Créer une nouvelle unité de mesure')
            ->setPageTitle('edit', 'Modifier une unité de mesure')
            ->setPageTitle('detail', 'Unités de mesure');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('unitname', 'Intitulé de l\'unité de mesure'),
            
        ];
    }
    
    

}
