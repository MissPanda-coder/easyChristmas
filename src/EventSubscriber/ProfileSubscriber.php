<?php

namespace App\EventSubscriber;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

class ProfileSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private Environment $twig;

    public function __construct(Security $security, Environment $twig)
    {
        $this->security = $security;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $user = $this->security->getUser();
        if ($user) {
            $profile = $user->getProfile(); // Assurez-vous que la méthode getProfile() existe dans votre entité User
            $this->twig->addGlobal('profile', $profile);
        }
    }
}
