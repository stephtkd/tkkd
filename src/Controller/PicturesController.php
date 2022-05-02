<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\AlbumPicture;
use App\Entity\CategoryAlbum;
use App\Entity\SlidePicture;
use App\Entity\Tag;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PicturesController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/pictures', name: 'app_pictures')] // page photos

    public function index(Request $request): Response
    {
        $SlidePictures = $this->entityManager->getRepository(SlidePicture::class)->findAll(); // affichage du slide photos configurable dans l'easyAdmin
        $AlbumPictures= $this->entityManager->getRepository(AlbumPicture::class)->findAll(); // affichage des cards qui presente les albums photos configurable dans l'easyAdmin
        $CategoriesAlbum= $this->entityManager->getRepository(CategoryAlbum::class)->findAll(); // affichage des catégories des albums photos configurable dans l'easyAdmin
        $Tags= $this->entityManager->getRepository(Tag::class)->findAll(); // affichage des tags des albums photos configurable dans l'easyAdmin


        $search = new Search(); //système de recherche pour les album photo
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $AlbumPictures = $this->entityManager->getRepository(AlbumPicture::class)->findWithSearch($search);
            // les tags aussi
        }
        else{
            $AlbumPictures = $this->entityManager->getRepository(AlbumPicture::class)->findAll();
            // les tags aussi
        }

        return $this->render('pictures/index.html.twig', [
            'SlidePictures' => $SlidePictures,
            'AlbumPictures' => $AlbumPictures,
            'CategoriesAlbum' => $CategoriesAlbum,
            'Tags' => $Tags,
            'form' => $form->createView()

        ]);
    }

    #[Route('/pictures/{slug}', name: 'app_pictures_slug')] // affichage des photos dans l'album photo configurable dans l'easyAdmin
    public function show($slug): Response
    {
        {
            $AlbumPicture = $this->entityManager->getRepository(AlbumPicture::class)->findOneBySlug($slug);


            if (!$AlbumPicture) {
                return $this->redirectToRoute('AlbumPictures');
            }

            return $this->render('pictures/AlbumPicture.html.twig', [
                'AlbumPicture' => $AlbumPicture,
            ]);

        }
    }
}
