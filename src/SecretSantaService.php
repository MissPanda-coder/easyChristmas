<?php

namespace App\Services;

use App\Entity\Draw;
use App\Entity\Exclusion;
use App\Entity\Assignation;
use Doctrine\ORM\EntityManagerInterface;

class SecretSantaService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateSecretSantaAssignments($drawId)
    {
        // Récupérer tous les participants
        $participants = $this->entityManager->getRepository(Draw::class)->find($drawId)->getParticipants();
        $exclusions = $this->entityManager->getRepository(Exclusion::class)->findBy(['draw' => $drawId]);

        $givers = $participants->toArray();
        $receivers = $participants->toArray();

        $assignments = [];

        foreach ($givers as $giver) {
            $validReceivers = array_filter($receivers, function($receiver) use ($giver, $exclusions) {
                foreach ($exclusions as $exclusion) {
                    if ($exclusion->getUserparticipant() === $giver && $exclusion->getUserexcluded() === $receiver) {
                        return false;
                    }
                }
                return true;
            });

            if (empty($validReceivers)) {
                throw new \Exception("No valid receivers found for giver: " . $giver->getId());
            }

            $receiver = $validReceivers[array_rand($validReceivers)];
            $assignments[] = [
                'draw' => $drawId,
                'user_giver' => $giver,
                'user_receiver' => $receiver
            ];

            // Remove the selected receiver from the list of available receivers
            $receivers = array_filter($receivers, function($r) use ($receiver) {
                return $r->getId() !== $receiver->getId();
            });
        }

        // Save assignments to database
        foreach ($assignments as $assignmentData) {
            $assignment = new Assignation();
            $assignment->setDraw($this->entityManager->getRepository(Draw::class)->find($assignmentData['draw']));
            $assignment->setUserGiver($assignmentData['user_giver']);
            $assignment->setUserReceiver($assignmentData['user_receiver']);
            $this->entityManager->persist($assignment);
        }

        $this->entityManager->flush();
    }
}
