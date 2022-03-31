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

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom de Famille'

            ])
            ->add('sex', ChoiceType::class, [
                'label' => 'Sexe',
                'choices'  => [
                        'Homme'=> true,
                        'Femme'=> true
                ]
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('streetAdress', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('nationality', TextType::class, [
                'label' => 'Nationalité'
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Téléphone'
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false
            ])
            ->add('level', ChoiceType::class,[
                'label' => 'Niveau',
                'choices'  => [
                    'Débutant' => true,
                    'Intermédiaire'=> true,
                    'Confirmé'=> true
                ]
            ])
            ->add('emergencyPhone', TelType::class, [
                'label' => "Téléphone d'urgence",
                 'attr'  => [
                    'placeholder' => 'Numéro de la personne à contacter en cas d\'urgence'
                 ]
            ])
            ->add('status', ChoiceType::class,[
                'label' => 'Status',
                'choices'  => [
                    'Elève' => true,
                    'Président'=> true,
                    'Trésorier'=> true,
                    'Secrétaire'=> true,
                    'Professeur'=> true,
                    'Assistant'=> true
                ]
            ])
            ->add('photoName', FileType::class, [
                'label' => 'Photo de l\'adhérent',
                'required' => false
            ])
            ->add('medicalCertificateName',FileType::class, [
                'label' => 'Certificat médical'
            ] )
           /* ->add('responsibleAdult', EntityType::class, [
                'class' => User::class,
                 'label' => 'Responsable Adulte'
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
