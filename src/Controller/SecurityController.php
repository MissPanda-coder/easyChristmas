<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\ResetPassword;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResetPasswordRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SecurityController extends AbstractController
{
    #[Route('/signup', name: 'signup')]
    public function signup(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        $user = new User();
        $registrationForm = $this->createForm(UserType::class, $user);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $hash = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            $em->persist($user);
            $em->flush();

            // Générer un token brut et le hacher
            $rawToken = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(30))), 0, 20);
            $hashedToken = sha1($rawToken);

            // Créer une nouvelle instance de vérification
            $rawToken = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(30))), 0, 20);
            $hashedToken = sha1($rawToken);

            $user->setVerificationToken($hashedToken);
            $user->setVerificationTokenExpiresAt(new \DateTimeImmutable('+2 hours'));

            $em->persist($user);
            $em->flush();

            // Envoyer l'e-mail de vérification
            $email = (new TemplatedEmail())
                ->from('no-reply@easychristmas.fr')
                ->to($user->getEmail())
                ->subject('Easy Christmas, merci de confirmer votre email')
                ->htmlTemplate('security/emailWelcome.html.twig')
                ->context([
                    'token' => $rawToken
                  ]);

            $mailer->send($email);

            $this->addFlash('success', 'Bienvenue sur Easy Christmas! Un email de confirmation vous a été envoyé.');

            return $this->redirectToRoute('login');
        }

        return $this->render('security/signup.html.twig', [
            'registrationForm' => $registrationForm->createView(),
            'page_title' => 'Espace Inscription',
            'sectionName' => 'signup',
        ]);
    }

    #[Route('/verify-email/{token}', name: 'verify_email')]
    public function verifyUserEmail(string $token, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        // Hacher le token reçu pour vérifier la correspondance avec celui envoyé
        $hashedToken = sha1($token);
        $user = $userRepository->findOneBy(['verificationToken' => $hashedToken]);

        
        // Vérifier si le token est valide et n'est pas expiré
        if (!$user || $user->getVerificationTokenExpiresAt() < new \DateTimeImmutable()) {
            $this->addFlash('error', 'Votre demande est expirée, veuillez refaire une demande.');
            return $this->redirectToRoute('signup');
        }

        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $user->setVerificationTokenExpiresAt(null);

        $em->persist($user);
        $em->flush();


        $this->addFlash('success', 'Votre adresse email a été vérifiée.');

        return $this->redirectToRoute('login');
    }
    
#[Route('/reset-password/{token}', name: 'reset-password')]
  public function resetPassword(RateLimiterFactory $passwordRecoveryLimiter, UserPasswordHasherInterface $userPasswordHasher, Request $request, EntityManagerInterface $em, string $token, ResetPasswordRepository $resetPasswordRepository)
  {
    $limiter = $passwordRecoveryLimiter->create($request->getClientIp());
    if (false === $limiter->consume(1)->isAccepted()) {
      $this->addFlash('error', 'Vous devez attendre une heure pour refaire une nouvelle demande');
      return $this->redirectToRoute('login');
    }

    $resetPassword = $resetPasswordRepository->findOneBy(['token' => sha1($token)]);
   
    if (!$resetPassword || $resetPassword->getExpiredAt() < new \DateTime('now')) {
      if ($resetPassword) {
        $em->remove($resetPassword);
        $em->flush();
      }
      $this->addFlash('error', 'Votre demande est expirée, veuillez refaire une demande.');
      return $this->redirectToRoute('login');
    }

    $passwordForm = $this->createFormBuilder()
      ->add('password', PasswordType::class, [
        'label' => 'Nouveau mot de passe',
        'constraints' => [
          new Length([
            'min' => 6,
            'minMessage' => 'Le mot de passe doit faire au moins 6 caractères.'
          ]),
          new NotBlank([
            'message' => 'Veuillez renseigner un mot de passe.'
          ])
        ]
      ])
      ->getForm();

    $passwordForm->handleRequest($request);
    if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
      $password = $passwordForm->get('password')->getData();
      $user = $resetPassword->getUser();
      $hash = $userPasswordHasher->hashPassword($user, $password);
      $user->setPassword($hash);
      $em->remove($resetPassword);
      $em->flush();
      $this->addFlash('success', 'Votre mot de passe a été modifié.');
      return $this->redirectToRoute('login');
    }

    return $this->render('security/resetpasswordform.html.twig', [
      'form' => $passwordForm->createView()
    ]);
  }
    
      #[Route('/reset-password-request', name: 'reset-password-request')]
      public function resetPasswordRequest(RateLimiterFactory $passwordRecoveryLimiter, MailerInterface $mailer, Request $request, UserRepository $userRepository, ResetPasswordRepository $resetPasswordRepository, EntityManagerInterface $em)
      {

        $limiter = $passwordRecoveryLimiter->create($request->getClientIp());
        if (false === $limiter->consume(1)->isAccepted()) {
          $this->addFlash('error', 'Vous devez attendre une heure pour refaire une récupération');
          return $this->redirectToRoute('login');
        }

        $emailForm = $this->createFormBuilder()->add('email', EmailType::class, [
          'constraints' => [
            new NotBlank([
              'message' => 'Veuillez renseigner votre email'
            ])
          ]
        ])->getForm();
        $emailForm->handleRequest($request);
        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
          $emailValue = $emailForm->get('email')->getData();
          $user = $userRepository->findOneBy(['email' => $emailValue]);
          if ($user) {
            $oldResetPassword = $resetPasswordRepository->findOneBy(['user' => $user]);
            if ($oldResetPassword) {
              $em->remove($oldResetPassword);
              $em->flush();
            }
            $resetPassword = new ResetPassword();
            $resetPassword->setUser($user);
            $resetPassword->setExpiredAt(new \DateTimeImmutable('+2 hours'));
            $token = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(30))), 0, 20);
            $hash = sha1($token);
            $resetPassword->setToken($hash);
            $em->persist($resetPassword);
            $em->flush();
            $email = new TemplatedEmail();
            $email->to($emailValue)
              ->subject('Demande de réinitialisation de mot de passe')
              ->htmlTemplate('security/resetpasswordrequest.html.twig')
              ->context([
                'token' => $token
              ]);
            $mailer->send($email);
          }
          $this->addFlash('success', 'Un email vous a été envoyé pour réinitialiser votre mot de passe');
          return $this->redirectToRoute('home');
        }
    
        return $this->render('security/resetpasswordrequest.html.twig', [
          'form' => $emailForm->createView()
        ]);
      }



    #[Route("/login", name: "login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('countdown');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'page_title' => 'Espace Connexion',
            'sectionName' => 'login',
        ]);
    }

    #[Route("/logout", name: "logout")]
    public function logout()
    {
        // Symfony gère la déconnexion automatiquement
    }
}
