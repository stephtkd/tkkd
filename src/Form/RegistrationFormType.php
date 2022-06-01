<?php

namespace App\Form;

use App\Entity\User;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void  //formulaire d'inscription de compte au site
    {
        $builder

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => new Length([
                    'min' => 2,
                    'max'=> 30
                ])
            ])

            ->add('lastName', TextType::class, [
                'label' => 'Nom de Famille',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30
                ])
            ])

            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => new Length([
                    'min' => 2,
                    'max'=> 60
                ])
            ])
            ->add('streetAddress', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal',
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => true,
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe est la confirmation doivent être identique!',
                'label' => 'Mot De Passe',
                'required' => true,
                'first_options' => [ 'label' => 'Mot De Passe'],
                'second_options' => [ 'label' => 'Confirmer votre Mot De Passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe', //Please enter a password
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères', //Your password should be at least {{ limit }} characters
                        // max length allowed by Symfony for security reasons
                        'max' => 4096
                    ])]
                ])

            ->add('captcha', CaptchaType::class, array(
                'width' => 200,
                'height' => 55,
                'invalid_message' => 'Le Captcha n\'est pas correcte'
            ))

            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'btn-block btn-dark'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
