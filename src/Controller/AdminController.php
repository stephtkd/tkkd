<?php

namespace App\Controller;

use App\Entity\EventPage;
use App\Form\EventsType;
use App\Repository\EventPageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/insert', name: 'insert', methods: 'GET|POST')]
    public function add(Request $request): Response
    {
        $event = new EventPage();

        $formEvents = $this->createForm(EventsType::class, $event);
        $formEvents->add('add', SubmitType::class, [
            'label' => 'Ajouter un événement',
        ]);

        $formEvents->handleRequest($request);

        if ($request->isMethod('post') && $formEvents->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $formEvents['linkImage']->getData();

            if (!is_string($file)) {
                $fileName = $file->getClientOriginalName();
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                $event->setLinkImage($fileName);
            } else {
                $session = $request->getSession();
                $session->getFlashBag()->add('message', 'Vous devez choisir une image pour l\'événement');
                $session->set('status', 'danger');

                return $this->redirect($this->generateUrl('insert'));
            }
            $em->persist($event);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'un nouveau événement a été ajouté');
            $session->set('status', 'success');

            return $this->redirect($this->generateUrl('list'));
        }

        return $this->render('Admin/admin.html.twig', [
            'my_form' => $formEvents->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update', methods: 'GET|POST')]
    public function update(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $eventsRepository = $em->getRepository(EventPage::class);
        $events = $eventsRepository->find($id);

        $img = $events->getLinkImage();

        $formEvents = $this->createForm(EventsType::class, $events);

        // Ajout bouton Submit

        $formEvents->add('add', SubmitType::class, [
            'label' => 'Mise à jour d\'un événement',
        ]);

        $formEvents->handleRequest($request);

        if ($request->isMethod('post') && $formEvents->isValid()) {
            // Insertion dans la BDD

            $file = $formEvents['linkImage']->getData();

            if (!is_string($file)) {
                $fileName = $file->getClientOriginalName();
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
                $events->setLinkImage($fileName);
            } else {
                $events->setLinkImage($img);
            }

            $em->persist($events);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'L\'événement a été mise à jour');
            $session->set('status', 'success');

            return $this->redirect($this->generateUrl('list'));
        }

        return $this->render('Admin/admin.html.twig', [
            'my_form' => $formEvents->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'GET|POST')]
    public function delete(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $eventsRepository = $em->getRepository(EventPage::class);
        $event = $eventsRepository->find($id);

        $em->remove($event);
        $em->flush();

        $session = $request->getSession();
        $session->getFlashBag()->add('message', 'L\'événement a été supprimé');
        $session->set('status', 'danger');

        return $this->redirect($this->generateUrl('list'));
    }
}
