<?php

namespace App\Controller\Admin;

use App\Entity\AlbumPicture;
use App\Entity\Contact;
use App\Entity\Event;
use App\Entity\EventOption;
use App\Entity\EventRate;
use App\Entity\EventSubscription;
use App\Entity\HomeComment;
use App\Entity\Member;
use App\Entity\Payment;
use App\Entity\SlidePicture;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')] //Dashboard de l'Admin avec EasyAdmin 4
    public function index(): Response
    {
        return $this->render('Admin/Dashboard.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        //$adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        //return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="build/LogoRond.png" style="width: 50%" class="mx-auto d-block" alt="logo Taekwonkido Phenix">')
            ->setFaviconPath('LogoRond.svg');
    }

    public function configureMenuItems(): iterable //les menus du dashboard pour geres le site
    {
        // Icones Menu fontawesome.com/icons
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('Gestion des comptes');
        yield MenuItem::linkToCrud('Comptes Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Tableau Adhérents', 'fas fa-table', Member::class)->setController(AdherantCrudController::class);
        yield MenuItem::linkToCrud('Tableau Membres', 'fas fa-table', Member::class)->setController(MemberCrudController::class);


        yield MenuItem::section('Gestion de la page d\'accueil');
        yield MenuItem::linkToCrud('Accueil', 'fas fa-pen', HomeComment::class);

        yield MenuItem::section('Gestion des photos');
        yield MenuItem::linkToCrud('Slide', 'fas fa-desktop', SlidePicture::class);
        yield MenuItem::linkToCrud('Tag Album Photo', 'fas fa-tags', Tag::class);
        yield MenuItem::linkToCrud('Album Photo', 'fas fa-images', AlbumPicture::class);

        yield MenuItem::section('Gestion des paiements');
        yield MenuItem::linkToCrud('Paiement', 'fas fa-list', Payment::class);

        yield MenuItem::section('Gestion de la page Contact');
        yield MenuItem::linkToCrud('Contact', 'fas fa-pen', Contact::class);

        yield MenuItem::section('Gestion des Evènements');
        yield MenuItem::linkToCrud('Evénements', 'fas fa-newspaper', Event::class)->setController(EventCrudController::class);
        yield MenuItem::linkToCrud('Adhésions', 'fas fa-newspaper', Event::class)->setController(AdhesionCrudController::class);
        yield MenuItem::linkToCrud('Inscriptions évènements', 'fas fa-newspaper', EventSubscription::class)->setController(EventSubscriptionCrudController::class);
        yield MenuItem::linkToCrud('Inscriptions adhésions', 'fas fa-newspaper', EventSubscription::class)->setController(AdhesionSubscriptionCrudController::class);
        yield MenuItem::linkToCrud('Tarifs Evenements', 'fas fa-cash-register', EventRate::class);
        yield MenuItem::linkToCrud('Options Evenements', 'fas fa-cash-register', EventOption::class);

    }
}
