<?php

namespace App\Controller\Admin;

use App\Entity\EventSubscription;
use App\Repository\EventSubscriptionRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class EventSubscriptionCrudController extends AbstractCrudController
{
    private EventSubscriptionRepository $eventSubscriptionRepository;

    public function __construct(EventSubscriptionRepository $eventSubscriptionRepository)
    {
        $this->eventSubscriptionRepository = $eventSubscriptionRepository;
    }

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
            ChoiceField::new('payment.status', 'Status paiement')
                ->setChoices([
                    'en attente'=> 'en attente',
                    'refusé'=> 'refusé',
                    'ok' => 'ok'
                ])
        ];
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Les labels utilisés pour faire référence à l'entité dans les titres, les boutons, etc.
            ->setEntityLabelInSingular('inscription')
            ->setEntityLabelInPlural('inscriptions')
            // Le titre visible en haut de la page et le contenu de l'élément <title>
            // Cela peut inclure ces différents placeholders : %entity_id%, %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer une %entity_label_singular%')
            ->setPageTitle('edit', 'Modifier une %entity_label_singular% <small>(#%entity_id%)</small>')
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
            ->disable(Action::EDIT)
            ->disable(Action::NEW);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $this->eventSubscriptionRepository->createQueryBuilder('es')
            ->leftJoin('es.event', 'e')
            ->andWhere('e.season IS NULL')
            ->orderBy('e.id', 'ASC');
    }
}
