<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DrawController extends AbstractController
{
    #[Route('/draw', name: 'draw')]
    public function index(): Response
    {
        return $this->render('draw/index.html.twig', [
            'page_title' => 'Tirage au sort de Noël - Qui offrira un cadeau à qui ?',
            'sectionName' => 'randomDraw',
            'controller_name' => 'DrawController',
            
        ]);
    }
}
