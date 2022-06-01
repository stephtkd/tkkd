<?php

namespace App\Controller\Admin;

use App\Entity\Payment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PaymentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Payment::class;
    }

    public function configureFields(string $pageName): iterable // configure les evenements dans la page Accueil et sa page "presentation de l'evenement"
    {
        $data = [
            IdField::new('id')->hideOnForm(),
            TextField::new('mean', 'Moyen')
                ->setFormTypeOption('disabled','disabled'),
            ChoiceField::new('status', 'Status')
                ->setChoices([
                    'en attente'=> 'en attente',
                    'refusé'=> 'refusé',
                    'ok' => 'ok'
                ]),
            TextField::new('reference', 'Ref HelloAsso')
                ->setFormTypeOption('disabled','disabled'),
            MoneyField::new('amount', 'Prix')->setCurrency('EUR')
                ->setFormTypeOption('disabled','disabled'),
            DateTimeField::new('date', 'Date'),
            TextareaField::new('detail', 'Détail')
                ->onlyOnDetail()
        ];

        return $data;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Les labels utilisés pour faire référence à l'entité dans les titres, les boutons, etc.
            ->setEntityLabelInSingular('Paiements')
            ->setEntityLabelInPlural('Paiement')
            // Le titre visible en haut de la page et le contenu de l'élément <title>
            // Cela peut inclure ces différents placeholders : %entity_id%, %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer un %entity_label_singular%')
            ->setPageTitle('edit', 'Modifier le %entity_label_singular% <small>(#%entity_id%)</small>')
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
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->displayIf(static function ($entity) {
                    return $entity->getMean() == 'Espèce';
                });
            })
            ->disable(Action::NEW);
    }
}
