<?php

namespace App\Controller;

use App\Entity\AlbumPicture;
use App\Entity\SlidePicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PicturesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/pictures', name: 'app_pictures')] // page photos

    public function index(): Response
    {
        $SlidePictures = $this->entityManager->getRepository(SlidePicture::class)->findAll(); // affichage du slide photos configurable dans l'easyAdmin
        $AlbumPictures= $this->entityManager->getRepository(AlbumPicture::class)->findAll(); // affichage des cards qui presente les albums photos configurable dans l'easyAdmin

        return $this->render('pictures/index.html.twig', [
            'SlidePictures' => $SlidePictures,
            'AlbumPictures' => $AlbumPictures

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
