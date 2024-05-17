<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CguController extends AbstractController
{
    #[Route('/cgu', name: 'cgu')]
    public function index(): Response
    {
        return $this->render('cgu/index.html.twig', [
            'page_title' => 'Les conditions générales d\'utilisation',
            'controller_name' => 'CguController'
        ]);
    }
}
