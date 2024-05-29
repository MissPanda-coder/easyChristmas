<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
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
    #[Route('/profile', name: 'profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function currentUserProfile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // Gérer le téléchargement de la photo
            $photoFile = $userForm->get('photo')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                // Déplacez le fichier dans le répertoire où les photos de profil sont stockées
                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }

                // Met à jour la propriété 'photo' pour stocker le nom du fichier
                $user->setPhoto($newFilename);
            }

            // Gérer le nouveau mot de passe
            $newPassword = $userForm->get('newPassword')->getData();
            if ($newPassword) {
                $hash = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hash);
            }

            $em->flush();
            $this->addFlash('success', 'Modifications sauvegardées !');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/index.html.twig', [
            'formProfile' => $userForm->createView(),
            'page_title' => 'Votre profil',
            'sectionName' => 'profile',
        ]);
    }
}
