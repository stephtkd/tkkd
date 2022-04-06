<?php

namespace App\Controller\Admin;

use App\Entity\AlbumPicture;
use App\Entity\AlbumPictureImages;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AlbumPictureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AlbumPicture::class;
    }


    public function configureFields(string $pageName): iterable
    {
         return[
            IdField::new('id')->hideOnForm(), // enleve l'affichage du id
            TextField::new('title', 'Titre de l\'album photo'),
            SlugField::new('slug')->setTargetFieldName('title'),
            TextEditorField::new('description', 'Description de l\'album')->setFormType(CKEditorType::class), // appel du CKEditor
             TextField::new('imageFile')->setFormType(VichImageType::class),
            ImageField::new('image', 'Image')
                ->setBasePath('upload/AlbumPicture/')
                ->setUploadDir('public/upload/AlbumPicture')->onlyOnIndex()
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),

        ];
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Album photo') //le titre de la page
            ->setDefaultSort(['id' => 'DESC'])  // id par ordre decroissant
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig'); //faire fonctionne CKEditor
    }

}
