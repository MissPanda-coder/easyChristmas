<?php

namespace App\Services;

use App\Entity\Draw;
use App\Entity\User;
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

    public function generateSecretSantaAssignments(array $participants)
    {
        $draw = new Draw();
        $this->entityManager->persist($draw);
        $this->entityManager->flush();

        // Create exclusions based on participants' data
        foreach ($participants as $participantData) {
            $participant = new User();
            $participant->setEmail($participantData['email']);
            $this->entityManager->persist($participant);

            if (!empty($participantData['exclusion'])) {
                $exclusion = new Exclusion();
                $exclusion->setUserparticipant($participant);
                $excludedUser = new User();
                $excludedUser->setEmail($participantData['exclusion']);
                $this->entityManager->persist($excludedUser);
                $exclusion->setUserexcluded($excludedUser);
                $this->entityManager->persist($exclusion);
            }
        }
        $this->entityManager->flush();

        // Retrieve all participants and exclusions
        $participants = $draw->getParticipants();
        $exclusions = $this->entityManager->getRepository(Exclusion::class)->findBy(['draw' => $draw]);

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
                'draw' => $draw->getId(),
                'user_giver' => $giver,
                'user_receiver' => $receiver
            ];

            $receivers = array_filter($receivers, function($r) use ($receiver) {
                return $r->getId() !== $receiver->getId();
            });
        }

        foreach ($assignments as $assignmentData) {
            $assignment = new Assignation();
            $assignment->setDraw($draw);
            $assignment->setUserGiver($assignmentData['user_giver']);
            $assignment->setUserReceiver($assignmentData['user_receiver']);
            $this->entityManager->persist($assignment);
        }

        $this->entityManager->flush();

        return $draw->getId();
    }
}
