<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/reset/password', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user) {
                // 1 : Enregistrer en base de demande de reset_password avec User, Token et CreatedAt
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // 2 : Envoyer un email à l'utilisateur avec le lien pour mettre à jour sont mdp
                $url = $this->generateUrl('new_password', [
                    'token' => $reset_password->getToken()
                ]);

                // Message sur le mail envoyé
                //$content = "Bonjour " . $user->getFirstname() . "<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site de Taekwonkido Phenix.<br/><br/>";
                //$content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $url . "'> mettre à jour votre mot de passe</a>.";

                // Système d'envoi du mail à la demande de réinitialisation du mdp + le message de confirmation d'envoi
               // $mail = new Mail();
              //  $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Réinitialiser Votre mot de passe de Taekwonkido Phenix', $content);
              //  $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un email avec la procédure pour réinitialiser votre mot de passe.');
            }
            else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'new_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $encoder): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }
        // Vérifier si le createdAt = now de 3h
        $now = new \DateTimeImmutable();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) { // à dépasser les 3 h
            $this->addFlash('notice', 'Votre demande de mot de passe a expirer. Merci de la renouveler.');
            return $this->redirectToRoute('reset_password');
        }
        // rendre une vue avec mdp et confirmer mdp
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();

            //encodage des nouveau mdp
            $password = $encoder->hashPassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($password);

            // flush en bdd
            $this->entityManager->flush();

            // redirection vers pas de connexion
            $this->addFlash('notice', 'Votre mot de passe à bien été mis à jour.');
            return $this->redirectToRoute('app_login');

        }

        return $this->render('reset_password/new_password.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
