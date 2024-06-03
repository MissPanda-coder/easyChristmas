<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof \App\Entity\User) {
            return;
        }

        if (!$user->getIsVerified()) {
           
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas encore vérifié. Veuillez vérifier votre e-mail.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        
    }
}
