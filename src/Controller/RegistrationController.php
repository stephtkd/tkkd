<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/register", name="app_register") //inscription/creation de compte au site
     */
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //valide par rapport au contraintes du formulaire

            $user = $form->getData();

            //dd($user);

           $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email) {
                $password = $encoder->hashPassword($user, $user->getPassword()); // mdp crypter
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush(); // éxecute la persistance, data (l'objet figé) = enregistre bdd

                // Envoie de mail pour confirmer l'inscription au site
                //$mail = new Mail();
                //$content = "Bonjour".$user->getFirstname()."<br/>Bienvenue sur le site de Taekwonkido Phenix.<br/><br/>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.";
                //$mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur Taekwonkido Phenix', $content);

                return $this->redirectToRoute('app_account');
            }
            else {
                $notification = "L'email que vous avez renseigné existe déjà.";
            }
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
         try {
           $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
          $this->addFlash('verify_email_error', $exception->getReason());

        return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse e-mail a été vérifiée.');//Your email address has been verified

        return $this->redirectToRoute('app_account');
    }
}
