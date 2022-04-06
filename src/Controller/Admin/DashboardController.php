<?php

namespace App\Controller\Admin;

use App\Entity\AlbumPicture;
use App\Entity\Contact;
use App\Entity\Event;
use App\Entity\HomeComment;
use App\Entity\Member;
use App\Entity\Membership;
use App\Entity\MembershipRate;
use App\Entity\SlidePicture;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
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
            ->setTitle('Taekwonkido Phenix');
    }

    public function configureMenuItems(): iterable //les menus du dashboard pour geres le site
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('Gestion des comptes');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);

        yield MenuItem::section('Gestion de la page d\'accueil');
        yield MenuItem::linkToCrud('Accueil', 'fas fa-pen', HomeComment::class);
        yield MenuItem::linkToCrud('Evénement', 'fas fa-newspaper', Event::class);


        yield MenuItem::section('Gestion des photos');
        yield MenuItem::linkToCrud('Slide', 'fas fa-desktop', SlidePicture::class);
        yield MenuItem::linkToCrud('Album Photo', 'fas fa-images', AlbumPicture::class);

        yield MenuItem::section('Gestion de la page Contact');
        yield MenuItem::linkToCrud('Contact', 'fas fa-pen', Contact::class);

        yield MenuItem::section('Gestion des Adhésions');
        yield MenuItem::linkToCrud('Tarif Adhésion', 'fas fa-cash-register', MembershipRate::class);


    }
}
