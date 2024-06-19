<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CountdownController extends AbstractController
{
    #[Route('/countdown', name: 'countdown')]
    public function countdown(): Response
    {
        return $this->render('countdown/index.html.twig', [
            'page_title' => 'Compte à rebours pour Noël - X dodos avant le grand jour !',
            'sectionName' => 'count',
            'controller_name' => 'CountdownController',
        ]);
    }
}
