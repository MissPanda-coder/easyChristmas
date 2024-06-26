<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ContactController extends AbstractController
{
    private $limiterFactory;

    public function __construct(RateLimiterFactory $contactFormLimiter)
    {
        $this->limiterFactory = $contactFormLimiter;
    }
    
    #[Route('/contact', name: 'contact')]
    public function contactMailing(Request $request, MailerInterface $mailer): Response
    {
        $limiter = $this->limiterFactory->create($request->getClientIp());

        if (false === $limiter->consume(1)->isAccepted()) {
            $this->addFlash('error', 'Trop de requêtes, veuillez réessayer plus tard.');
            return $this->redirectToRoute('contact');
        }

        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $data = $contactForm->getData();

            try {
                $email = (new TemplatedEmail())
                    ->from($data['email'])
                    ->to('no-reply@easyChristmas.fr')
                    ->subject($data['subject'])
                    ->htmlTemplate('contact/email.html.twig')
                    ->context([
                        'data' => $data,
                    ]);

                $mailer->send($email);

                return $this->redirectToRoute('thanks');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de l\'email.');
            }
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $contactForm,
            'page_title' => 'Nous contacter',
            'sectionName' => 'contact',
            'content' => 'content',
            'controller_name' => 'ContactController',
        ]);
    }

    #[Route('/contact/thanks', name: 'thanks')]
    public function thanks(): Response
    {
        return $this->render('contact/thanks.html.twig', [
            'page_title' => 'Message envoyé !',
            'sectionName' => 'thanks',
            'controller_name' => 'ContactController',
        ]);
    }
}
