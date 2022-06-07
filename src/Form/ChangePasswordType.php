<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void //formulaire pour modifier le password dans le dashboard utilisateur
    {
        $builder

            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])

            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])

            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])

            ->add('phone_number', TelType::class, [
                'label' => 'Numéro de téléphone'
            ])

            ->add('street_address', TextType::class, [
                'label' => 'Adresse'
            ])

            ->add('postal_code', TextType::class, [
                'label' => 'Code postal'
            ])

            ->add('City', TextType::class, [
                'label' => 'Ville'
            ])

            ->add('old_password', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
            ])

            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Le mot de passe est la confirmation doivent être identique!',
                'label' => 'Nouveau Mot de passe',
                'first_options' => [ 'label' => 'Nouveau mot de passe'],
                'second_options' => [ 'label' => 'Confirmer le nouveau mot De Passe'],
            ])

            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'attr' => [
                    'class' => 'btn-block btn-dark'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
