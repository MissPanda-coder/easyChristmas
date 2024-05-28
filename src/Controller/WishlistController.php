<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'wishlist')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('wishlist/index.html.twig', [
            'page_title' => 'Créez et partagez votre liste de vœux pour Noël',
            'sectionName' => 'wishlist',
            'controller_name' => 'WishlistController',
        ]);
    }
}
