<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }


    public function configureFields(string $pageName): iterable // configure la page contact
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextEditorField::new('description', 'Description')->setFormType(CKEditorType::class),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Les labels utilisés pour faire référence à l'entité dans les titres, les boutons, etc.
            ->setEntityLabelInSingular('le Contenu de la Page Contact')
            ->setEntityLabelInPlural('Contenu de la Page Contact')
            // Le titre visible en haut de la page et le contenu de l'élément <title>
            // Cela peut inclure ces différents placeholders : %entity_id%, %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste du Contenu de la Page Contact')
            ->setPageTitle('new', 'Créer le Contenu de la Page Contact')
            ->setPageTitle('edit', 'Modifier %entity_label_singular% <small>(#%entity_id%)</small>')
            ->setDefaultSort(['id' => 'DESC'])
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ;
    }
}
