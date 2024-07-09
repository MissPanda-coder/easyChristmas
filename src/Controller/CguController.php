<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CguController extends AbstractController
{
    #[Route('/conditions-generales-utilisation', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render('cgu/index.html.twig', [
            'page_title' => 'CGU',
            'sectionName' => 'cgu',
            'content' => 'content',
            'controller_name' => 'CguController',
        ]);
    }
}
