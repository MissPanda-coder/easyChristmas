<?php

namespace App\Controller\Admin;

use App\Entity\Draw;
use App\Entity\Unit;
use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Wishes;
use App\Entity\Ingredient;
use App\Entity\Assignation;
use App\Entity\Recipecategory;
use App\Entity\Recipedifficulty;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
#[IsGranted('ROLE_SUPER_ADMIN', message: 'You are not allowed to access the admin dashboard.')]
class DashboardController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin')]
    public function adminDashboard(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if ($user) {
            dump($user->getRoles()); // Ajoutez cette ligne pour vérifier les rôles
        }

        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EasyChristmas Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Administration du site');
        yield MenuItem::linkToDashboard('Retour DashBoard', 'fa-solid fa-house-laptop');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        yield MenuItem::section('RECETTES');
        yield MenuItem::linkToCrud('Recettes', 'fa-solid fa-flask', Recipe::class);
        yield MenuItem::linkToCrud('Catégories', 'fa-solid fa-list', Recipecategory::class);
        yield MenuItem::linkToCrud('Niveaux de difficulté', 'fa-solid fa-poo-storm', Recipedifficulty::class);
        yield MenuItem::linkToCrud('Ingrédients', 'fa-solid fa-pepper-hot', Ingredient::class);
        yield MenuItem::linkToCrud('Unités de mesure', 'fa-solid fa-ruler', Unit::class);

        yield MenuItem::section('TIRAGES');
        yield MenuItem::linkToCrud('Année du Tirage', 'fa-solid fa-calendar-days', Draw::class);
        yield MenuItem::linkToCrud('Résultat du tirage', 'fa-solid fa-person-chalkboard', Assignation::class);

        yield MenuItem::section('VOEUX');
        yield MenuItem::linkToCrud('Année de la liste', 'fa-solid fa-calendar-days', Wishes::class);
        yield MenuItem::linkToCrud('Auteur de la liste', 'fa-solid fa-gift', User::class);



        yield MenuItem::section('Retour au site');
        yield MenuItem::linkToRoute('Accueil Site','fa-solid fa-house','home');
        yield MenuItem::linkToRoute('Vue Profil','fa-solid fa-eye','profile');
    }
}
