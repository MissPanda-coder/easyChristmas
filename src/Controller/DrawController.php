<?php

namespace App\Controller;

use App\Entity\Draw;
use App\Entity\Assignation;
use App\Services\SecretSantaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DrawController extends AbstractController
{
    private $secretSantaService;

    public function __construct(SecretSantaService $secretSantaService)
    {
        $this->secretSantaService = $secretSantaService;
    }

    #[Route('/draw', name: 'draw', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function draw(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            try {
                $drawId = $this->secretSantaService->generateSecretSantaAssignments($data['participants']);
                return $this->json(['success' => true, 'drawId' => $drawId]);
            } catch (\Exception $e) {
                return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->render('draw/index.html.twig', [
            'page_title' => 'Tirage au sort de Noël - Qui offrira un cadeau à qui ?',
            'sectionName' => 'randomDraw',
            'controller_name' => 'DrawController',
        ]);
    }

    #[Route('/draw/results/{drawId}', name: 'draw_results')]
    public function drawResults($drawId, EntityManagerInterface $entityManager): Response
    {
        $draw = $entityManager->getRepository(Draw::class)->find($drawId);
        $assignments = $entityManager->getRepository(Assignation::class)->findBy(['draw' => $draw]);

        return $this->render('draw/results.html.twig', [
            'draw' => $draw,
            'assignments' => $assignments,
        ]);
    }
}
