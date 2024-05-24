<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profile;
use App\Form\ProfileType;
use App\Components\ProfileFormComponent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\TwigComponent\ComponentFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{   
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/profile', name: 'profile')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
     
            $profile = new Profile();
            $profile->setUser($user);
         
        
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($profile);
            $this->entityManager->flush();
            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/index.html.twig', [
            'profile' => $profile,
            'form' => $form->createView()
        ]);

}
}
