<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeadTitleController
{

    #[Route("/", name : "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig', [
            'page_title' => 'Plateforme interactive de Noël"'
        ]);
    }


    #[Route("/countdown", name : "countdown")]
    public function countdown(): Response
    {
        return $this->render('countdown.html.twig', [
            'page_title' => 'Compte à rebours pour Noël - X dodos avant le grand jour !'
        ]);
    }


    #[Route("/draw", name : "draw")]
    public function draw(): Response
    {
        return $this->render('draw.html.twig', [
            'page_title' => 'Tirage au sort de Noël - Qui offrira un cadeau à qui ?'
        ]);
    }


    #[Route("/wishlist", name : "wishlist")]
    public function wishlist(): Response
    {
        return $this->render('wishlist.html.twig', [
            'page_title' => 'Créez et partagez votre liste de vœux pour Noël'
        ]);
    }


    #[Route("/recipes", name : "recipes")]
    public function recipes(): Response
    {
        return $this->render('recipes.html.twig', [
            'page_title' => 'Recettes de Noël pour tous les goûts'
        ]);
    }


    #[Route("/contact", name : "contact")]
    public function contact(): Response
    {
        return $this->render('contact.html.twig', [
            'page_title' => 'Nous contacter'
        ]);
    }

    #[Route("/cgu", name : "cgu")]
    public function cgu(): Response
    {
        return $this->render('cgu.html.twig', [
            'page_title' => 'Les conditions generales d\'utilisation'
        ]);
    }

  #[Route("/pdc", name : "pdc")]
    public function pdc(): Response
    {
        return $this->render('pdc.html.twig', [
            'page_title' => 'La politique de confidentialité'
        ]);
    }


    #[Route("/profile", name : "profile")]
    public function profile(): Response
    {
        return $this->render('profile.html.twig', [
            'page_title' => 'Votre Profil'
        ]);
    }


}
