<?php

namespace App\Controller\Admin;

use App\Entity\Member;
use Doctrine\ORM\QueryBuilder;
use App\Repository\EventRepository;
use App\Repository\MemberRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class AdherantCrudController extends AbstractCrudController
{
    private EventRepository $eventRepository;
    private MemberRepository $memberRepository;

    public function __construct(EventRepository $eventRepository, MemberRepository $memberRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->memberRepository = $memberRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Member::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('photoName', 'Photo de l\'adhérent')
                ->hideOnIndex()
                ->setBasePath('upload/member')
                ->setUploadDir('public/upload/member')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            EmailField::new('email', 'Email'),
            TelephoneField::new('phoneNumber', 'Téléphone'),
            ChoiceField::new('sex', 'Sexe')
                ->hideOnIndex()
                ->setChoices([
                    'Homme'=> 'Homme',
                    'Femme'=> 'Femme'
                ]),
            DateField::new('birthdate', 'Date de naissance'),
            TextField::new('streetAddress', 'Adresse'),
            NumberField::new('postalCode', 'Code Postal'),
            TextField::new('city', 'Ville'),
            TextField::new('nationality', 'Nationnalité'),
            AssociationField::new('responsibleAdult', 'Adulte Responsable')
                ->renderAsNativeWidget(),
            TelephoneField::new('emergencyPhone', 'Téléphone d\'urgence'),
            ChoiceField::new('status', 'Status')
            ->setChoices([
                'Elève' => 'Elève',
                'Président'=> 'Président',
                'Trésorier'=> 'Trésorier',
                'Secrétaire'=> 'Secrétaire',
                'Professeur'=> 'Professeur',
                'Assistant'=> 'Assistant'
            ]),
            BooleanField::new('instructor', 'Instructeur'),
            BooleanField::new('bureau', 'Membre du bureau'),
            ChoiceField::new('level', 'Grade')
                ->setChoices([
                    'aucun'=> 'aucun',
                    '14e keup'=> '14e keup',
                    '13e keup'=> '13e keup',
                    '12e keup' => '12e keup',
                    '11e keup'=> '11e keup',
                    '10e keup'=> '10e keup',
                    '9e keup' => '9e keup',
                    '8e keup'=> '8e keup',
                    '7e keup'=> '7e keup',
                    '6e keup' => '6e keup',
                    '5e keup'=> '5e keup',
                    '4e keup'=> '4e keup',
                    '3e keup'=> '3e keup',
                    '2e keup'=> '2e keup',
                    '1er keup' => '1er keup',
                    'BanDan'=> 'BanDan',
                    '1er Dan/Poom'=> '1er Dan/Poom',
                    '2e Dan/Poom' => '2e Dan/Poom',
                    '3e Dan/Poom'=> '3e Dan/Poom',
                    '4e Dan'=> '4e Dan',
                    '5e Dan' => '5e Dan',
                    '6e Dan'=> '6e Dan',
                    '7e Dan'=> '7e Dan',
                    '8e Dan'=> '8e Dan',
                    '9e Dan'=> '9e Dan'
                ]),
            TextEditorField::new('comment', 'Commentaire')->setFormType(CKEditorType::class),
            TextField::new('Adhesion', 'Adhésion')->hideOnForm()
        ];
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Les labels utilisés pour faire référence à l'entité dans les titres, les boutons, etc.
            ->setEntityLabelInSingular('un adhérent au club')
            ->setEntityLabelInPlural('Adhérents au club')
            // Le titre visible en haut de la page et le contenu de l'élément <title>
            // Cela peut inclure ces différents placeholders : %entity_id%, %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer %entity_label_singular%')
            ->setPageTitle('edit', 'Modifier %entity_label_singular% <small>(#%entity_id%)</small>')
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
            ->disable(Action::NEW);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $actualAdhesion = $this->eventRepository->findActualAdhesion();
        $futureAdhesion = $this->eventRepository->findNextAdhesion();

        return $this->memberRepository->createQueryBuilder('m')
            ->leftJoin('m.eventSubscriptions', 'es')
            ->andWhere('es.event = :actual OR es.event = :future')
            ->andWhere('es.status = :status')
            ->setParameter('actual', $actualAdhesion)
            ->setParameter('future', $futureAdhesion)
            ->setParameter('status', 'ok')
            ->orderBy('m.id', 'ASC');
    }
}
