<?php

namespace App\Controller;

use App\Entity\AlbumPicture;
use App\Entity\Tag;
use App\Repository\AlbumPictureRepository;
use App\Repository\TagRepository;
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
    public function show(
        $slug,
        PaginatorInterface $paginator,
        Request $request,
        AlbumPictureRepository $albumPictureRepository
    ): Response
    {
        $albumPictures = $albumPictureRepository->findBy([], ['id' => 'DESC']);

        $albumPictures = $paginator->paginate(
            $albumPictures, // Requête contenant les données à paginer (ici les albums photos)
            $request->query->getInt('page', 1), //page number
            4 //limit per page
        );

        $Tag = $this->entityManager->getRepository(Tag::class)->findOneBySlug($slug);


        if (!$Tag) {
            return $this->redirectToRoute('Tags');
        }

        return $this->render('tag/index.html.twig', [
            'Tag' => $Tag,
            'AlbumPictures' => $albumPictures

        ]);
    }
}
