<?php

namespace App\Controller;

use App\Entity\Draw;
use App\Entity\User;
use App\Form\DrawType;
use App\Entity\Exclusion;
use App\Entity\Assignation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\SecretSantaService;

class DrawController extends AbstractController
{
    private $secretSantaService;

    public function __construct(SecretSantaService $secretSantaService)
    {
        $this->secretSantaService = $secretSantaService;
    }

    #[Route('/draw', name: 'draw')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DrawType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $participantsData = $data['participants'];

            if (empty($participantsData)) {
                return new Response('No participants provided', Response::HTTP_BAD_REQUEST);
            }

            // Créer un nouveau tirage
            $draw = new Draw();
            $draw->setDrawdate(new \DateTime());
            $draw->setDrawyear((int)date('Y'));

            $entityManager->persist($draw);
            $entityManager->flush();

            // Ajouter les participants et exclusions au tirage
            foreach ($participantsData as $participantData) {
                $email = $participantData['email'];
                $exclusionEmail = $participantData['exclusion'];

                $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($user) {
                    $draw->addParticipant($user);
                    if ($exclusionEmail) {
                        $excludedUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $exclusionEmail]);
                        if ($excludedUser) {
                            $exclusion = new Exclusion();
                            $exclusion->setDraw($draw);
                            $exclusion->setUserparticipant($user);
                            $exclusion->setUserexcluded($excludedUser);
                            $entityManager->persist($exclusion);
                        }
                    }
                }
            }

            $entityManager->flush();

            try {
                $this->secretSantaService->generateSecretSantaAssignments($draw->getId());
                return $this->redirectToRoute('draw_results', ['drawId' => $draw->getId()]);
            } catch (\Exception $e) {
                return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->render('draw/index.html.twig', [
            'page_title' => 'Tirage au sort de Noël - Qui offrira un cadeau à qui ?',
            'sectionName' => 'randomDraw',
            'form' => $form->createView(),
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
