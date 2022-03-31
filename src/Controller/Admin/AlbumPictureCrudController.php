<?php

namespace App\Controller\Admin;

use App\Entity\AlbumPicture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class AlbumPictureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AlbumPicture::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // enleve l'affichage du id
            TextField::new('title', 'Titre de l\'album photo'),
            SlugField::new('slug')->setTargetFieldName('title'),
            TextEditorField::new('description', 'Description de l\'album')->setFormType(CKEditorType::class),
            ImageField::new('illustration', 'Album photo')
                ->setBasePath('upload/')
                ->setUploadDir('public/upload')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),

        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Album photo')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

}
