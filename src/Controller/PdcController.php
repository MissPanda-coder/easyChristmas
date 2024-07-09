<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PdcController extends AbstractController
{
    #[Route('/politique-de-confidentialite', name: 'pdc')]
    public function pdc(): Response
    {
        return $this->render('pdc/index.html.twig', [
            'page_title' => 'Politique de confidentialité',
            'sectionName' => 'pdc',
            'content' => 'content',
            'controller_name' => 'PdcController'
        ]);
    }
}
