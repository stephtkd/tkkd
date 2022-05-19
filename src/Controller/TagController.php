<?php

namespace App\Controller;

use App\Entity\AlbumPicture;
use App\Entity\Tag;
use App\Repository\AlbumPictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/pictures/tag/{slug}', name: 'app_tag_slug')] // page
    public function show($slug): Response
    {
        $Tag = $this->entityManager->getRepository(Tag::class)->findOneBySlug($slug);
        $albumPictures = $this->entityManager->getRepository(AlbumPicture::class)->findBy([], ['id' => 'DESC']);

        if (!$Tag) {
            return $this->redirectToRoute('Tags');
        }

        return $this->render('tag/index.html.twig', [
            'Tags' => $Tag,
            'AlbumPictures' => $albumPictures,
        ]);
    }
}
