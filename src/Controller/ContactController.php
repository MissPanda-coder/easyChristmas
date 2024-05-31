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

        $contactForm = $this->createForm(ContactType::class, $data);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {

            try {
            $email = (new TemplatedEmail())
            ->from($data->email)
            ->to('adeliine@hotmail.com')
            ->subject($data->subject)
            ->htmlTemplate('contact/email.html.twig')
            ->context([
                'data' => $data,]);
        
            $mailer->send($email);
    
            return $this->redirectToRoute('thanks');


        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue');

            $mailer->send($email);
    }
}
    return $this->render('contact/index.html.twig', [
        'contactForm' => $contactForm,
        'page_title' => 'Nous contacter',
        'sectionName' => 'contact',
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