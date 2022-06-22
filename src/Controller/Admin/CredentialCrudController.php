<?php

namespace App\Controller\Admin;

use App\Entity\Credential;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class CredentialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Credential::class;
    }


    public function configureFields(string $pageName): iterable // configure la page des infos API
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('apiKey', 'Clé API'),
            BooleanField::new('active','Actif'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Les labels utilisés pour faire référence à l'entité dans les titres, les boutons, etc.
            ->setEntityLabelInSingular('le Contenu de la Page Info API')
            ->setEntityLabelInPlural('Contenu de la Page Info API')
            // Le titre visible en haut de la page et le contenu de l'élément <title>
            // Cela peut inclure ces différents placeholders : %entity_id%, %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste du %entity_label_plural%')
            ->setPageTitle('new', 'Créer %entity_label_singular%')
            ->setPageTitle('edit', 'Modifier %entity_label_singular% <small>(#%entity_id%)</small>')
            ->setDefaultSort(['id' => 'DESC'])
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ;
    }
}
