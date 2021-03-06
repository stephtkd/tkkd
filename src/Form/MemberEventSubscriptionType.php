<?php

namespace App\Form;

use App\Entity\EventOption;
use App\Entity\EventRate;
use App\Entity\EventSubscription;
use App\Entity\Member;
use App\Form\DataTransformer\NumberToEventOptionTransformer;
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
    private $transformer;

    public function __construct(NumberToEventOptionTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('member', EntityType::class, [
                'class' => Member::class,
                'label' => false,
                'query_builder' => function (MemberRepository $mr) use($options) {
                    return $mr->findForEventSubscription($options);
                },
            ])
            ->add('eventRate', EntityType::class, [
                'class' => EventRate::class,
                'label' => false,
                'query_builder' => function (EventRateRepository $evr) {
                    return $evr->createQueryBuilder('er')
                        ->orderBy('er.amount', 'ASC');
                },
            ])
            ->add('eventOptions', ChoiceType::class, [
                'label' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => $options['eventOptions']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider l\'inscription',
                'attr' => [
                    'class' => 'btn-block btn-dark'
                ]
            ])
        ;

        $builder->get('eventOptions')
            ->addModelTransformer($this->transformer);
        
    }


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