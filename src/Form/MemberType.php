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
            ->add('streetAdress', TextType::class, [
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
            ->add('level', ChoiceType::class,[
                'label' => 'Grade',
                'required' => true,
                'choices'  => [
                    'aucun'=> 'aucun',
                    '14e keup'=> '14e keup',
                    '13e keup'=> '13e keup',
                    '12e keup' => '12e keup',
                    '11e keup'=> '11e keup',
                    '10e keup'=> '10e keup',
                    '9e keup' => '9e keup',
                    '8e keup'=> '8e keup',
                    '7e keup'=> '7e keup',
                    '6e keup' => '6e keup',
                    '5e keup'=> '5e keup',
                    '4e keup'=> '4e keup',
                    '3e keup'=> '3e keup',
                    '2e keup'=> '2e keup',
                    '1er keup' => '1er keup',
                    'BanDan'=> 'BanDan',
                    '1er Dan/Poom'=> '1er Dan/Poom',
                    '2e Dan/Poom' => '2e Dan/Poom',
                    '3e Dan/Poom'=> '3e Dan/Poom',
                    '4e Dan'=> '4e Dan',
                    '5e Dan' => '5e Dan',
                    '6e Dan'=> '6e Dan',
                    '7e Dan'=> '7e Dan',
                    '8e Dan'=> '8e Dan',
                    '9e Dan'=> '9e Dan'
                ]
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
