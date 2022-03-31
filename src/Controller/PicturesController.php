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

    #[Route('/pictures', name: 'app_pictures')]

    public function index(): Response
    {
        $SlidePictures = $this->entityManager->getRepository(SlidePicture::class)->findAll();
        $AlbumPictures= $this->entityManager->getRepository(AlbumPicture::class)->findAll();

        return $this->render('pictures/index.html.twig', [
            'SlidePictures' => $SlidePictures,
            'AlbumPictures' => $AlbumPictures

        ]);
    }

    #[Route('/pictures/{slug}', name: 'app_pictures_slug')]
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
