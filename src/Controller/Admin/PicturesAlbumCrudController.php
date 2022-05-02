<?php

namespace App\Controller\Admin;

use App\Entity\PicturesAlbum;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class PicturesAlbumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PicturesAlbum::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            ImageField::new('name')
                ->setBasePath('upload/') //système d'upload des images
                ->setUploadDir('public/upload')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }

}
