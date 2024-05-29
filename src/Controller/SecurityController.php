<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class SecurityController extends AbstractController
{

    public function __construct(
        private FormLoginAuthenticator $authenticator
    ) {
    }

    #[Route('/signup', name: 'signup')]
    public function signup(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $registrationform = $this->createForm(UserType::class, $user);
        $registrationform->handleRequest($request);
        
        if ($registrationform->isSubmitted() && $registrationform->isValid()) {
            $hash = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setUsername($registrationform->get('username')->getData());
            $user->setEmail($registrationform->get('email')->getData());
    
            $em->persist($user);
            $em->flush();
            
            $this->addFlash('success', 'Bienvenue sur Easy Christmas!');
            return $this->redirectToRoute('login');
        }
        
        return $this->render('security/signup.html.twig', [
            'registrationform' => $registrationform->createView(),
            'page_title' => 'Espace Inscription',
            'sectionName' => 'signup',
        ]);
    }
   


    #[Route("/login", name: "login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('login');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'page_title' => 'Espace Connexion',
            'sectionName' => 'login',
        ]);
    }

    #[Route("/logout", name: "logout")]
    public function logout()
    {
    }
   
}