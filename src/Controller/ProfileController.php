<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        // Rechercher un profil existant pour l'utilisateur
        $profile = $this->entityManager->getRepository(Profile::class)->findOneBy(['user' => $user]);

        // Si aucun profil n'existe, en créer un nouveau
        if (!$profile) {
            $profile = new Profile();
            $profile->setUser($user);
        }

        $formProfile = $this->createForm(ProfileType::class, $profile);
        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $this->entityManager->persist($profile);
            $this->entityManager->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/index.html.twig', [
            'profile' => $profile,
            'formProfile' => $formProfile->createView(),
            'page_title' => 'Bienvenue sur votre profil',
            'sectionName' => 'profile',
        ]);
    }
}
