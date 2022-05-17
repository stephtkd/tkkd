<?php

namespace App\Form;

use App\Entity\AlbumPicture;
use App\Entity\PicturesAlbum;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class AlbumPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Intégrer un système de multi upload d'image avec AlbumPictureType dans l'EasyAdmin (AlbumPictureCrudController)
            // Il n'est pas lié à la base de données (mapped à false)
            ->add('images', FileType::class, [
                'multiple' => true,
                //'mapped' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => PicturesAlbum::class,

        ]);
    }
}
