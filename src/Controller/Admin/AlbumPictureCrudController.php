<?php

namespace App\Controller\Admin;

use App\Entity\AlbumPicture;
use App\Form\AlbumPictureType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


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
            SlugField::new('slug', 'Création de l\'URL')
                ->setTargetFieldName('title'),
            TextEditorField::new('description', 'Description de l\'album')
                ->setFormType(CKEditorType::class), // appel du CKEditor
            AssociationField::new('categoryAlbum', 'Catégorie de l\'album'),
            AssociationField::new('Tag', 'Tag de l\'album'),
            ImageField::new('picture', 'image principal de l\'album')
                 ->setBasePath('upload/') //système d'upload des images
                 ->setUploadDir('public/upload')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
             // Intégrer un système de multi upload d'image avec AlbumPictureType
           /* ImageField::new('PictureAlbum', 'Photos de l\'album')
                ->setBasePath('upload/') //système d'upload des images
                ->setUploadDir('public/upload')
                ->setFormType(AlbumPictureType::class)
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),*/
        ];
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Les labels utilisés pour faire référence à l'entité dans les titres, les boutons, etc.
            ->setEntityLabelInSingular('Album Photo')
            ->setEntityLabelInPlural('Albums Photos')
            // Le titre visible en haut de la page et le contenu de l'élément <title>
            // Cela peut inclure ces différents placeholders : %entity_id%, %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer un %entity_label_singular%')
            ->setPageTitle('edit', 'Modifier l\'%entity_label_singular% <small>(#%entity_id%)</small>')
            // Définit le tri initial appliqué à la liste
            // (l'utilisateur peut ensuite modifier ce tri en cliquant sur les colonnes de la table)
            ->setDefaultSort(['id' => 'DESC'])
            //la vue de CKEditor
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
        ;
    }

}