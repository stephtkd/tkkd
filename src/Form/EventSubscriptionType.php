<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventRate;
use App\Entity\EventSubscription;
use App\Entity\Member;
use App\Entity\Payment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventSubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status',TextType::class)
            // ->add('medicalCertificateName')
            // ->add('comment')
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'label' => 'Événement'
            ])
            ->add('member', EntityType::class, [
                'class' => Member::class,
                'label' => 'Membre à inscrire'
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' =>'Utilisateur'
            ])
            ->add('eventRate', EntityType::class, [
                'class' => EventRate::class,
                'label'=> 'Les tarifs'
            ])
            ->add('eventOptions', EntityType::class, [
                'class' => EventRate::class,
                'label'=> 'Les options',
                'multiple' => true
            ])
            ->add('payment', EntityType::class, [
                'class' => Payment::class,
                'label' => 'Paiement'
            ])
            ->add('save', SubmitType::class,[
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventSubscription::class,
        ]);
    }
}
