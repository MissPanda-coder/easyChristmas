<?php

namespace App\Controller;

use App\Contact\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {

        $data = new Contact();
        $contactForm = $this->createForm(ContactType::class, $data);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $data = $contactForm->getData();
            return $this->redirectToRoute('profile');
       
    }
    return $this->render('contact/index.html.twig', [
        'contactForm' => $contactForm->createView(),
        'page_title' => 'Nous contacter',
        'sectionName' => 'contact',
        'controller_name' => 'ContactController',
    ]);

}
}