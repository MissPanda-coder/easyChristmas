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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{

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
        $userForm->remove('password');
        $userForm->add('newPassword', PasswordType::class, ['label' => 'Nouveau mot de passe', 'required' => false]);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $photoFile = $userForm->get('photo')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                // Move the file to the directory where profile photos are stored
                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );

                    // Remove the old photo if it exists
                    if ($user->getPhoto()) {
                        unlink($this->getParameter('photos_directory') . '/' . $user->getPhoto());
                    }

                    // Update the 'photo' property to store the file name
                    $user->setPhoto($newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
            }

            $newPassword = $user->getNewPassword();
            if ($newPassword) {
                $hash = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hash);
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
