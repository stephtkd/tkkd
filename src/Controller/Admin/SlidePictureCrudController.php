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
            // Les labels utilisés pour faire référence à l'entité dans les titres, les boutons, etc.
            ->setEntityLabelInSingular('une Photo du Slide')
            ->setEntityLabelInPlural('Photos pour le Slide')
            // Le titre visible en haut de la page et le contenu de l'élément <title>
            // Cela peut inclure ces différents placeholders : %entity_id%, %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste des Photos pour le Slide')
            ->setPageTitle('new', 'Créer une Photo du Slide')
            ->setPageTitle('edit', 'Modifier %entity_label_singular% <small>(#%entity_id%)</small>')
            // Définit le tri initial appliqué à la liste
            // (l'utilisateur peut ensuite modifier ce tri en cliquant sur les colonnes de la table)
            ->setDefaultSort(['id' => 'DESC'])
            ;
    }
}
