<?php

namespace App\Controller;

use App\Entity\Draw;
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
    #[Route('/draw', name: 'draw_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, EntityManagerInterface $em, MailerInterface $mailer, UserRepository $userRepository): Response
    {
        if ($request->isMethod('POST')) {
            $data = json_decode($request->request->get('participants_data'), true);

            if (!$data || !isset($data) || !is_array($data)) {
                return new JsonResponse(['success' => false, 'message' => 'Invalid data received.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $participants = $data;
            $result = $this->performDraw($participants);

            if ($result['success']) {
                $draw = new Draw();
                $draw->setDrawDate(new \DateTime());
                $draw->setDrawyear((new \DateTime())->format('Y'));

                foreach ($result['pairs'] as $pair) {
                    $participant = $userRepository->findOneBy(['email' => $pair['giver']]);
                    $recipient = $userRepository->findOneBy(['email' => $pair['receiver']]);

                    if (!$participant || !$recipient) {
                        return new JsonResponse(['success' => false, 'message' => 'Participant or recipient not found.'], JsonResponse::HTTP_BAD_REQUEST);
                    }

                    // Send email to the participant
                    $email = (new TemplatedEmail())
                        ->from('no-reply@easychristmas.fr')
                        ->to($participant->getEmail())
                        ->subject('Résultat du tirage au sort')
                        ->htmlTemplate('draw/emailResults.html.twig')
                        ->context([
                            'giver' => $participant->getEmail(),
                            'receiver' => $recipient->getEmail(),
                        ]);

                    $mailer->send($email);

                    // Add participants to the draw
                    $draw->addParticipant($participant);
                    $draw->addParticipant($recipient);
                }

                $em->persist($draw);
                $em->flush();

                return $this->redirectToRoute('draw_results', ['id' => $draw->getId()]);
            } else {
                return new JsonResponse(['success' => false, 'message' => 'Un problème est survenu lors du tirage au sort.']);
            }
        }

        return $this->render('draw/index.html.twig', [
            'page_title' => 'Tirage au sort',
            'sectionName' => 'draw',
            'controller_name' => 'DrawController',
        ]);
    }

    private function performDraw($participants)
    {
        $pairs = [];
        $givers = array_column($participants, 'email');
        $receivers = array_column($participants, 'email');
        $exclusions = array_column($participants, 'exclusion', 'email');

        // Shuffle the givers to ensure randomness
        shuffle($givers);

        foreach ($givers as $giver) {
            $receiver = $this->findValidReceiver($giver, $receivers, $exclusions);
            if ($receiver === null) {
                return ['success' => false];
            }

            $pairs[] = ['giver' => $giver, 'receiver' => $receiver];
            // Remove the chosen receiver from the list
            $receivers = array_diff($receivers, [$receiver]);
        }

        return ['success' => true, 'pairs' => $pairs];
    }

    private function findValidReceiver($giver, $receivers, $exclusions)
    {
        // Shuffle the receivers to ensure randomness
        shuffle($receivers);

        foreach ($receivers as $receiver) {
            if ($receiver !== $giver && (!isset($exclusions[$giver]) || $exclusions[$giver] !== $receiver)) {
                return $receiver;
            }
        }

        return null;
    }

    #[Route('/draw/results/{id}', name: 'draw_results')]
    public function drawResults(Draw $draw): Response
    {
        $participants = $draw->getParticipants();

        $pairs = [];
        foreach ($participants as $giver) {
            $receiver = $this->findReceiverForGiver($giver, $participants, $draw->getExclusions());
            if ($receiver) {
                $pairs[] = ['giver' => $giver, 'receiver' => $receiver];
            }
        }

        return $this->render('draw/results.html.twig', [
            'draw' => $draw,
            'pairs' => $pairs,
            'page_title' => 'Résultats du tirage au sort',
            'sectionName' => 'drawResults',
        ]);
    }

    private function findReceiverForGiver($giver, $participants, $exclusions)
    {
        foreach ($participants as $participant) {
            if ($participant !== $giver && !$this->isExcluded($giver, $participant, $exclusions)) {
                return $participant;
            }
        }

        return null;
    }

    private function isExcluded($giver, $receiver, $exclusions)
    {
        foreach ($exclusions as $exclusion) {
            if (($exclusion->getUserparticipant() === $giver && $exclusion->getUserexcluded() === $receiver) ||
                ($exclusion->getUserparticipant() === $receiver && $exclusion->getUserexcluded() === $giver)) {
                return true;
            }
        }

        return false;
    }
}
