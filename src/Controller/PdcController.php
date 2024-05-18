<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PdcController extends AbstractController
{
    #[Route('/pdc', name: 'pdc')]
    public function index(): Response
    {
        return $this->render('pdc/index.html.twig', [
            'page_title' => 'Politique de confidentialité',
            'sectionName' => 'pdc',
            'controller_name' => 'PdcController'
        ]);
    }
}
