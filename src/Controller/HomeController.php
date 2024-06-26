<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home/index.html.twig', [
            'page_title' => 'Plateforme interactive de Noël',
            'sectionName' => 'home',
            'content' => 'content',
            'controller_name' => 'HomeController',
        ]);
    }
}
