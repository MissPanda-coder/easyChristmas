<?php

namespace App\Controller\Admin;

use App\Entity\Profile;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Profile::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Définir le titre pour la page 'index' (liste des entités)
            ->setPageTitle('index', 'Profils')
            // Définir le titre pour la page 'new' (création d'une entité)
            ->setPageTitle('new', 'Créer un nouveau profil')
            // Définir le titre pour la page 'edit' (modification d'une entité)
            ->setPageTitle('edit', 'Modifier un profil')
            // Définir le titre pour la page 'detail' (détails d'une entité)
            ->setPageTitle('detail', 'Profils');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('photo', 'Photo de profil (png, jpg)'),
            TextField::new('userName', 'Nom d\'utilisateur'),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
        ];
    }
    
}
