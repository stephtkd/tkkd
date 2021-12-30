<?php

namespace App\Controller;

use App\Entity\EventPage;
use App\Form\EventsType;
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

    #[Route('/update/{id}', name: 'update', methods: 'GET|PUT')]
    public function update(Request $request, $id): Response
    {
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'GET|DELETE', priority: 10)]
    public function delete(Request $request, $id): Response
    {
    }
}
