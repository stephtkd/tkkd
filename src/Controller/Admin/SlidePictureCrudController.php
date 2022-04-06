<?php

namespace App\Controller\Admin;

use App\Entity\SlidePicture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SlidePictureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SlidePicture::class;
    }


    public function configureFields(string $pageName): iterable // configure le slide photo dans la page photos
    {
        return [
            IdField::new('id')->hideOnForm(), // enleve l'affichage du id
            TextField::new('title', 'Titre de la photo'),
            ImageField::new('illustration', 'Photo du Slide')
                ->setBasePath('upload/')
                ->setUploadDir('public/upload')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
        ];
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Slide des photos');
    }
}
