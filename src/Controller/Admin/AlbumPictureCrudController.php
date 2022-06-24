<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Fields\MultipleImageField;
use App\Controller\Admin\Fields\MultiTagField;
use App\Entity\AlbumPicture;
use App\Form\MultiTagType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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


            // Intégrer un système de multi selection des Tags, peut être avec TomSelect ou Select2
            MultiTagField::new('Tag', 'Tags de l\'album')
                ->setColumns('col-8'),

            // fonction de base pour les Tags dans l'EasyAdmin
            //AssociationField::new('Tag', 'Tags de l\'album'),


            ImageField::new('picture', 'Image principal de l\'album')
                ->setBasePath('upload/album') //système d'upload des images
                ->setUploadDir('public/upload/album')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
             // Intégrer un système de multi upload d'image avec AlbumPictureType
            MultipleImageField::new('picturesAlbums', 'Photos ou vidéos de l\'album')
                ->onlyOnForms()
                ->setColumns('col-6'),

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
            ->setPageTitle('index', 'Liste des albums photo')
            ->setPageTitle('new', 'Créer un album photo')
            ->setPageTitle('edit', 'Modifier l\'%entity_label_singular% <small>(#%entity_id%)</small>')
            // Définit le tri initial appliqué à la liste
            // (l'utilisateur peut ensuite modifier ce tri en cliquant sur les colonnes de la table)
            ->setDefaultSort(['id' => 'DESC'])
            //la vue de CKEditor
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
}
