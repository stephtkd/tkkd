<?php

namespace App\Controller;

use App\Entity\AlbumPicture;
use App\Entity\PicturesAlbum;
use App\Form\AlbumPictureType;
use App\Repository\AlbumPictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/album/picture')]
class AlbumPictureController extends AbstractController
{
    #[Route('/', name: 'app_album_picture_index', methods: ['GET'])]
    public function index(AlbumPictureRepository $albumPictureRepository): Response
    {
        return $this->render('album_picture/index.html.twig', [
            'album_pictures' => $albumPictureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_album_picture_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $albumPicture = new AlbumPicture();
        $form = $this->createForm(AlbumPictureType::class, $albumPicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $PicutresAlbums = $form->get('PicturesAlbum')->getData();

            // On boucle sur les images
            foreach($PicutresAlbums as $PicutresAlbum){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$PicutresAlbum->guessExtension();

                // On copie le fichier dans le dossier uploads
                $PicutresAlbum->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $PicutresAlbum = new PicturesAlbum();
                $PicutresAlbum->setName($fichier);
                $albumPicture->addPicturesAlbum($PicutresAlbum);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($albumPicture);
            $entityManager->flush();

            return $this->redirectToRoute('app_pictures_slug');

        }

        return $this->renderForm('album_picture/new.html.twig', [
            'albumPicture' => $albumPicture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_album_picture_show', methods: ['GET'])]
    public function show(AlbumPicture $albumPicture): Response
    {
        return $this->render('album_picture/show.html.twig', [
            'album_picture' => $albumPicture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_album_picture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AlbumPicture $albumPicture, AlbumPictureRepository $albumPictureRepository): Response
    {
        $form = $this->createForm(AlbumPictureType::class, $albumPicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumPictureRepository->add($albumPicture);
            return $this->redirectToRoute('app_album_picture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album_picture/edit.html.twig', [
            'album_picture' => $albumPicture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_album_picture_delete', methods: ['POST'])]
    public function delete(Request $request, AlbumPicture $albumPicture, AlbumPictureRepository $albumPictureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$albumPicture->getId(), $request->request->get('_token'))) {
            $albumPictureRepository->remove($albumPicture);
        }

        return $this->redirectToRoute('app_album_picture_index', [], Response::HTTP_SEE_OTHER);
    }
}
