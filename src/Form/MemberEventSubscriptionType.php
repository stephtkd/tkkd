<?php

namespace App\Form;

use App\Entity\EventOption;
use App\Entity\EventRate;
use App\Entity\EventSubscription;
use App\Entity\Member;
use App\Repository\EventOptionRepository;
use App\Repository\EventRateRepository;
use App\Repository\MemberRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberEventSubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('member', EntityType::class, [
                'class' => Member::class,
                // 'mapped' => false,
                'label' => false,
                'query_builder' => function (MemberRepository $mr) use($options) {
                    return $mr->findForEventSubscription($options);
                },
            ])
            ->add('eventRate', EntityType::class, [
                'class' => EventRate::class,
                // 'mapped' => false,
                'label' => false,
                'query_builder' => function (EventRateRepository $evr) {
                    return $evr->createQueryBuilder('er')
                        ->orderBy('er.amount', 'ASC');
                },
            ])
            ->add('eventOption', ChoiceType::class, [
                // 'mapped' =>false,
                'label' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => $options['eventOptions']
                // 'choice_label' => function ($eventOptions) {
                //     return $eventOptions->getName();
                // }
                // 'query_builder' => function (EventOptionRepository $eor) {
                //     return $eor->createQueryBuilder('eo')
                //         ->orderBy('eo.name', 'ASC');
                // },
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider l\'inscription',
                'attr' => [
                    'class' => 'btn-block btn-dark'
                ]
            ])
        ;
        // $builder->addEventListener(
        //     FormEvents::PRE_SET_DATA,
        //     [$this,'preSetDataOnMember']
        // );
        
    }

    // public function preSetDataOnMember(FormEvent $event): void
    // {
    //     $form = $event->getForm();
    //     $data = $event->getData();
    //     dump($form,$data);

    //     // unset($form->get('member'));
    //     // $form->get('member')->setData($data->getEventSubscriptions()[0]->getMember());
    //     // $form->get('member')->setData($data->getEventSubscriptions()[0]->getMember());
    // }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventSubscription::class,
            'csrf_protection' => false,
            'responsibleAdult' => "",
            'event' =>"",
            'eventOptions' => []
        ]);
    }
}