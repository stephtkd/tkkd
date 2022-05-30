<?php

namespace App\Controller\Admin;

use App\Entity\EventSubscription;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventSubsciptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EventSubscription::class;
    }

    public function configureFields(string $pageName): iterable // configure les evenements dans la page Accueil et sa page "presentation de l'evenement"
    {
        return [
            AssociationField::new('event', 'Evènement')->renderAsNativeWidget(),
            AssociationField::new('member', 'Membre')->renderAsNativeWidget(),
            AssociationField::new('user', 'Utilisateur')->renderAsNativeWidget(),
            AssociationField::new('eventRate', 'Tarif')->renderAsNativeWidget(),
            AssociationField::new('eventOptions', 'Options')->renderAsNativeWidget()
                ->setFormTypeOption('by_reference', false),
            AssociationField::new('payment', 'Paiement')->renderAsNativeWidget(),
            TextField::new('status', 'Statut'),
        ];
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Les labels utilisés pour faire référence à l'entité dans les titres, les boutons, etc.
            ->setEntityLabelInSingular('Evenements')
            ->setEntityLabelInPlural('Evenement')
            // Le titre visible en haut de la page et le contenu de l'élément <title>
            // Cela peut inclure ces différents placeholders : %entity_id%, %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer un %entity_label_singular%')
            ->setPageTitle('edit', 'Modifier l\' %entity_label_singular% <small>(#%entity_id%)</small>')
            // Définit le tri initial appliqué à la liste
            // (l'utilisateur peut ensuite modifier ce tri en cliquant sur les colonnes de la table)
            ->setDefaultSort(['id' => 'DESC'])
            //CKEditor
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
