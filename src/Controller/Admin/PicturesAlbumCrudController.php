<?php

namespace App\Controller\Admin;

use App\Entity\PicturesAlbum;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            TextField::new('name'),
            ImageField::new('images')
            ->setBasePath('upload/')
        ->setUploadDir('public/upload')
        ->setUploadedFileNamePattern('[randomhash].[extension]')
        ->setRequired(false),
        ];
    }

}
