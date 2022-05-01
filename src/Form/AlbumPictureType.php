<?php

namespace App\Form;

use App\Entity\AlbumPicture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Intégrer un système de multi upload d'image avec AlbumPictureType dans l'EasyAdmin (AlbumPictureCrudController)
            // Il n'est pas lié à la base de données (mapped à false)
            ->add('PicturesAlbum', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AlbumPicture::class,
        ]);
    }
}
