<?php

namespace App\Contact;

use Symfony\Component\Validator\Constraints as Assert;
class Contact{

    #[Assert\NotBlank]
    #[Assert\Length(
        min:3,
        max:150
    )]
    public string $name = '';
  
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
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