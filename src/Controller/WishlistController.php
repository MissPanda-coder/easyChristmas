<?php

namespace App\Controller;

use App\Entity\Wishes;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssignationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'wishlist_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function sendWishes(Request $request, EntityManagerInterface $em, MailerInterface $mailer, AssignationRepository $assignationRepository): Response
    {
        if ($request->isMethod('POST')) {
            $wishesYear = new DateTimeImmutable();
            $wishnamesJson = $request->request->get('participants_data_wishes');
            $wishnames = json_decode($wishnamesJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Traiter l'erreur de décodage JSON
                $this->addFlash('error', 'Erreur lors du décodage des données JSON des vœux.');
                return $this->redirectToRoute('wishlist_index');
            }

            if (is_array($wishnames)) {
                foreach ($wishnames as $wishname) {
                    if (!empty($wishname)) {
                        $newWish = new Wishes();
                        $newWish->setCreatedAt($wishesYear);
                        $newWish->setWishname($wishname);
                        $newWish->setUser($this->getUser());
                        $em->persist($newWish);
                        $em->flush();

                        // Récupérez les assignations après avoir persisté le vœu.
                        foreach ($newWish->getAssignations() as $assignation) {
                            $email = (new TemplatedEmail())
                                ->from('no-reply@easychristmas.fr')
                                ->to($assignation->getUserGiver()->getEmail())
                                ->subject('Liste de voeux')
                                ->htmlTemplate('wishlist/emailWishes.html.twig')
                                ->context([
                                    'receiver' => $assignation->getUserReceiver()->getEmail(),
                                    'wishname' => $wishname,
                                ]);

                            $mailer->send($email);
                        }
                    }
                }

                // Persister toutes les entities
                $em->flush();

                $this->addFlash('success', 'Votre liste de voeux a été envoyée.');
            } else {
                $this->addFlash('error', 'Les données des vœux sont invalides.');
            }
        }

        return $this->render('wishlist/index.html.twig', [
            'page_title' => 'Liste de voeux',
            'sectionName' => 'wishes',
            'content' => 'wishesContent',
            'controller_name' => 'WishlistController',
        ]);
    }
}
