<?php

namespace App\Contact;

use Symfony\Component\Validator\Constraints as Assert;
class Contact{

    #[Assert\NotBlank]
    #[Assert\Length(
        min:3,
        minMessage: 'Le nom doit faire au moins 3 caractères.',
        max:150
    )]
    public string $name = '';
  
    #[Assert\NotBlank]
    #[Assert\Email(message: 'Veuillez entrer un email valide.')]
    public string $email = '';

    #[Assert\NotBlank(message:'L\'objet du message ne peut pas être vide.')]
    #[Assert\Length(
        min:3,
        max:30
    )]
    public string $subject = '';

    #[Assert\NotBlank] 
    #[Assert\Length(
        min:3,
        max:250
    )]
    public string $message = '';
}