<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom de Famille',
                'required' => true

            ])
            ->add('sex', ChoiceType::class, [
                'label' => 'Sexe',
                'required' => true,
                'choices'  => [
                    'Homme'=> 'Homme',
                    'Femme'=> 'Femme'
                ]
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
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
            ->add('nationality', TextType::class, [
                'label' => 'Nationalité',
                'required' => true,
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false
            ])
            ->add('emergencyPhone', TelType::class, [
                'label' => "Téléphone d'urgence",
                'required' => false,
                 'attr'  => [
                    'placeholder' => 'Numéro de la personne à contacter en cas d\'urgence'
                 ]
            ])
            ->add('photoName', FileType::class, [
                'mapped' => false,
                'label' => 'Photo de l\'adhérent',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider l\'inscription',
                'attr' => [
                    'class' => 'btn-block btn-dark'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
            'csrf_protection' => false,
        ]);
    }
}
