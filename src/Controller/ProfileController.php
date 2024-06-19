<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/user/{id}', name: 'user_info')]
        public function setSuperAdmin(EntityManagerInterface $entityManager, int $id): Response
    {
        // Récupérer l'utilisateur par son ID
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        // Mettre à jour les rôles de l'utilisateur
        $roles = $user->getRoles();
        if (!in_array('ROLE_SUPER_ADMIN', $roles)) {
            $roles[] = 'ROLE_SUPER_ADMIN';
            $user->setRoles($roles);
            $entityManager->flush();
        }

        return new Response('Rôle ROLE_SUPER_ADMIN ajouté à l\'utilisateur avec ID '.$id);
    }
    
    #[Route('/user/{id}', name: 'user')]
    #[IsGranted('ROLE_USER')]
    public function userProfile(User $user): Response
    {
        $currentUser = $this->getUser();
        if ($currentUser === $user) {
            return $this->redirectToRoute('profile');
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/profile', name: 'profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {   
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $userForm = $this->createForm(ProfileType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $photo = $userForm->get('photoFile')->getData();
            if ($photo) {
                // Supprimer l'ancienne photo si elle existe
                if ($user->getPhoto()) {
                    $oldPhotoPath = $this->getParameter('photos_directory') . '/' . basename($user->getPhoto());
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                // Générer un nouveau nom de fichier pour la nouvelle photo
                $ext = $photo->guessExtension();
                $filename = bin2hex(random_bytes(10)) . '.' . $ext;

                // Déplacer la nouvelle photo dans le dossier de destination
                try {
                    $photo->move(
                        $this->getParameter('photos_directory'),
                        $filename
                    );

                    // Mettre à jour le chemin de la nouvelle photo de l'utilisateur
                    $user->setPhoto($filename);
                } catch (FileException $e) {
                     // Gérer l'exception si quelque chose se passe mal pendant le téléchargement
                }
            }
            $em->flush();
            $this->addFlash('success', 'Modifications sauvegardées !');
        
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/index.html.twig', [
            'userForm' => $userForm->createView(),
            'page_title' => 'Votre profil',
            'sectionName' => 'profile',
        ]);
    }
}

