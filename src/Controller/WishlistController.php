<?php

namespace App\Controller;

use App\Entity\Wishes;
use App\Form\WishType;
use App\Form\WishesType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssignationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'wishlist_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function sendWishes(Request $request, EntityManagerInterface $em, MailerInterface $mailer, AssignationRepository $assignationRepository): Response
    {
        $wishForm = $this->createForm(WishType::class);

        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $wishesYear = $wishForm->get('wishesyear')->getData();
            $wishnames = $wishForm->get('wishnames')->getData();

            foreach ($wishnames as $wishname) {
                if (!empty($wishname)) {
                    $newWish = new Wishes();
                    $newWish->setWishesyear($wishesYear);
                    $newWish->setWishname($wishname);
                    $newWish->setUser($this->getUser());
                    $em->persist($newWish);

                    // Envoyer l'email au donneur pour chaque assignation
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

            $em->flush();

            return new JsonResponse(['success' => true, 'message' => 'Wishes sent successfully.']);
        }

        return $this->render('wishlist/index.html.twig', [
            'wishForm' => $wishForm->createView(),
            'page_title' => 'Liste de voeux',
            'sectionName' => 'wishes',
            'controller_name' => 'WishlistController',
        ]);
    }
}
