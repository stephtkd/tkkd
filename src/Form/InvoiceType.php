<?php

namespace App\Form;

use App\Entity\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('eventSubscriptions', CollectionType::class, [
            'entry_type' => MemberEventSubscriptionType::class,
            'label' =>false,
            // 'entry_options' => $options,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
            'responsibleAdult' => "",
            'event' =>"",
            'eventOptions' => []
        ]);
    }
}