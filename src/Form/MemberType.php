<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            /*->add('sex', ChoiceType::class, [
                'label' => 'Sexe',
                'required' => true,
                'choices'  => [
                    '' => 0,
                    'Homme'=> 1,
                    'Femme'=> 2
                ]
            ])*/
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
                'required' => true,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false
            ])
           /* ->add('level', ChoiceType::class,[
                'label' => 'Grade',
                'choices'  => [
                    'aucun' => 0,
                    '14e keup'=> 1,
                    '13e keup'=> 2,
                    '12e keup' => 3,
                    '11e keup'=> 4,
                    '10e keup'=> 5,
                    '9e keup' => 6,
                    '8e keup'=> 7,
                    '7e keup'=> 8,
                    '6e keup' => 9,
                    '5e keup'=> 10,
                    '4e keup'=> 11,
                    '3e keup'=> 12,
                    '2e keup'=> 13,
                    '1e keup' => 14,
                    'BanDan'=> 15,
                    '1er Dan/Poom'=> 16,
                    '2e Dan/Poom' => 17,
                    '3e Dan/Poom'=> 18,
                    '4e Dan'=> 19,
                    '5e Dan' => 20,
                    '6e Dan'=> 21,
                    '7e Dan'=> 22,
                    '8e Dan'=> 23,
                    '9e Dan'=> 24
                ]
            ])*/
            ->add('emergencyPhone', TelType::class, [
                'label' => "Téléphone d'urgence",
                'required' => true,
                 'attr'  => [
                    'placeholder' => 'Numéro de la personne à contacter en cas d\'urgence'
                 ]
            ])
            /*->add('status', ChoiceType::class,[
                'label' => 'Status',
                'choices'  => [
                    'Elève' => 1,
                    'Président'=> 2,
                    'Trésorier'=> 3,
                    'Secrétaire'=> 4,
                    'Professeur'=> 5,
                    'Assistant'=> 6
                ]
            ])*/
            ->add('photoName', FileType::class, [
                'label' => 'Photo de l\'adhérent',
                'required' => false
            ])
            ->add('medicalCertificateName',FileType::class, [
                'label' => 'Certificat médical',
                'required' => true,
            ] )
           /* ->add('responsibleAdult', EntityType::class, [
                'class' => User::class,
                 'label' => 'Responsable Adulte',
                'required' => false
            ])*/
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
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
