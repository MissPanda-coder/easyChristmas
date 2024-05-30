<?php

namespace App\Controller;

use App\Contact\Contact;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $data = new Contact();

        // $data->lastname = 'Jane';
        // $data->email = 'jane@me.com';
        // $data->message = 'Hi, Jane. I wanted to reach out to you about your new website.';
        
        
        $contactForm = $this->createForm(ContactType::class, $data);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to('adeliine@hotmail.com')
            ->subject('demande de contact')
            ->htmlTemplate('contact/email.html.twig')
            ->context([
                'data' => $data,]);

            $mailer->send($email);
            
       
    }
    return $this->render('contact/index.html.twig', [
        'contactForm' => $contactForm->createView(),
        'page_title' => 'Nous contacter',
        'sectionName' => 'contact',
        'controller_name' => 'ContactController',
    ]);

}
}