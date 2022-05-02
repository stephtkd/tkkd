<?php

namespace App\Form;

use App\Entity\PicturesAlbum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PicturesAlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('images', FileType::class,[
                'label' => false,
                // 'multiple' => true,
                'attr' => [
                    'multiple' => 'multiple'
                ], 'required' => false, 'data_class' => null, 'multiple' => true,
                'mapped' => false,
                //'required' => false
            ])
            ->add('AlbumPicture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PicturesAlbum::class,
        ]);
    }
}
