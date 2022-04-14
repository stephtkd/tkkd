<?php

namespace App\Controller;

use App\Entity\CategoryAlbum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryAlbumController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/pictures/category_album', name: 'app_category_album')]
    public function index(): Response
    {
        return $this->render('category_album/index.html.twig');
    }

    #[Route('/pictures/category_album/{slug}', name: 'app_category_album_slug')] // page
    public function show($slug): Response
    {
        $categoryAlbum = $this->entityManager->getRepository(CategoryAlbum::class)->findOneBySlug($slug);

        if (!$categoryAlbum) {
            return $this->redirectToRoute('CategoriesAlbum');
        }

        return $this->render('category_album/index.html.twig', [
            'CategoryAlbum' => $categoryAlbum,

        ]);
    }
}
