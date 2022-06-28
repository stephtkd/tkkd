<?php

// src/Form/TagType.php
namespace App\Form;

use App\Entity\Member;
use App\Entity\Tug;
use App\Repository\MemberRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TugType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name');
        $builder->add('member', EntityType::class, [
            'class' => Member::class,
            'label' => false,
            'query_builder' => function (MemberRepository $mr) use($options) {
                return $mr->findForEventSubscription($options);
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tug::class,
            'responsibleAdult' => "",
            'event' =>"",
        ]);
    }
}