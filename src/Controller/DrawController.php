<?php

namespace App\Controller;

use App\Entity\Draw;
use App\Entity\Assignation;
use App\Entity\Exclusion;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DrawController extends AbstractController
{
    #[Route('/tirage-au-sort', name: 'draw_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    
    public function index(Request $request, EntityManagerInterface $em, MailerInterface $mailer, UserRepository $userRepository): Response
    {
        
        if ($request->isMethod('POST')) {
            $data = json_decode($request->request->get('participants_data'), true);

            if (!$data || !is_array($data)) {
                return new JsonResponse(['success' => false, 'message' => 'Donnée non valide'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $pairs = $data;
            $draw = new Draw();
            $draw->setDrawDate(new \DateTime());
            $draw->setDrawyear((new \DateTime())->format('Y'));

            foreach ($pairs as $pair) {
                $giver = $userRepository->findOneBy(['email' => $pair['giver']]);
                $receiver = $userRepository->findOneBy(['email' => $pair['receiver']]);

                if (!$giver || !$receiver) {
                    return new JsonResponse(['success' => false, 'message' => 'Donneur ou receveur non trouvé'], JsonResponse::HTTP_BAD_REQUEST);
                }

                // Création et persist des résultats
                $assignation = new Assignation();
                $assignation->setDraw($draw);
                $assignation->setUserGiver($giver);
                $assignation->setUserReceiver($receiver);
                $em->persist($assignation);

                // Exclusions
                if (!empty($pair['exclusion'])) {
                    $excludedUser = $userRepository->findOneBy(['email' => $pair['exclusion']]);
                    if ($excludedUser) {
                        $exclusion = new Exclusion();
                        $exclusion->setDraw($draw);
                        $exclusion->setUserparticipant($giver);
                        $exclusion->setUserexcluded($excludedUser);
                        $em->persist($exclusion);
                    }
                }

                // Mail aux participants
                $email = (new TemplatedEmail())
                    ->from('no-reply@easychristmas.fr')
                    ->to($giver->getEmail())
                    ->subject('Résultat du tirage au sort')
                    ->htmlTemplate('draw/emailResults.html.twig')
                    ->context([
                        'giver' => $giver->getEmail(),
                        'receiver' => $receiver->getEmail(),
                    ]);

                $mailer->send($email);

                // Ajout des participants à draw
                $draw->addParticipant($giver);
                $draw->addParticipant($receiver);

                // Délai entre les envois d'email pour ne pas planter mailtrap
                usleep(500000);
            }

            $em->persist($draw);
            $em->flush();

            return $this->redirectToRoute('draw_results', ['id' => $draw->getId()]);
        }

        return $this->render('draw/index.html.twig', [
            'page_title' => 'Tirage au sort',
            'sectionName' => 'draw',
            'content' => 'content',
            'controller_name' => 'DrawController',
        ]);
    }

    #[Route('/tirage-au-sort/resultats/{id}', name: 'draw_results', methods: ['GET'])]
 
    public function drawResults(Draw $draw): Response
    {
        $assignations = $draw->getAssignations();
        $exclusions = $draw->getExclusions();

        return $this->render('draw/results.html.twig', [
            'draw' => $draw,
            'assignations' => $assignations,
            'exclusions' => $exclusions,
            'page_title' => 'Résultats du tirage au sort',
            'sectionName' => 'drawResults',
            'content' => 'content',
        ]);
    }
}
