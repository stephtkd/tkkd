<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureFields(string $pageName): iterable // configure les evenements dans la page Accueil et sa page "presentation de l'evenement"
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Titre'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextEditorField::new('description', 'Description')->setFormType(CKEditorType::class),
            NumberField::new('maximum_number_of_participants', 'Nombre maximum participants'),
            NumberField::new('adult_rate', 'Nombre d\'adulte'),
            NumberField::new('child_rate', 'Nombre d\'enfant'),
            DateTimeField::new('start_date', 'Début de l\'événement'),
            DateTimeField::new('end_date', 'Fin de l\'événement'),
            DateTimeField::new('registration_deadline', 'Date limite d\'inscription'),
            MoneyField::new('price', 'Tarif')->setCurrency('EUR'),
            ImageField::new('link_image', 'Image')
               ->setBasePath('upload/')
                ->setUploadDir('public/upload')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
        ];
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Créer un événements')
            ->setDefaultSort(['id' => 'DESC'])
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
}
