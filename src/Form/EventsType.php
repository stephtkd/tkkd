<?php

namespace App\Form;

use App\Entity\EventPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de l\'événement : '])
            ->add('price', NumberType::class, ['label' => 'Premier prix'])
            ->add('linkImage', FileType::class, ['label' => 'Image :', 'required' => false, 'data_class' => null, 'empty_data' => 'aucune image'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventPage::class,
        ]);
    }
}
