<?php

namespace App\Controller;


use App\Classe\Search;
use App\Entity\AlbumPicture;
use App\Entity\CategoryAlbum;
use App\Entity\PicturesAlbum;
use App\Entity\SlidePicture;
use App\Entity\Tag;
use App\Form\SearchType;
use App\Repository\AlbumPictureRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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

    public function index(
        Request $request,
        AlbumPictureRepository $albumPictureRepository,
        PaginatorInterface $paginator
    ): Response
    {
        // systeme de pagination
        $albumPictures = $albumPictureRepository->findBy([], ['id' => 'DESC']);

        $albumPictures = $paginator->paginate(
            $albumPictures, // Requête contenant les données à paginer (ici les albums photos)
            $request->query->getInt('page', 1), //page number
            4 //limit per page
        );

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



        $SlidePictures = $this->entityManager->getRepository(SlidePicture::class)->findAll(); // affichage du slide photos configurable dans l'easyAdmin
        $Tags= $this->entityManager->getRepository(Tag::class)->findAll(); // affichage des tags des albums photos configurable dans l'easyAdmin


        return $this->render('pictures/index.html.twig', [
            'SlidePictures' => $SlidePictures,
            'Tags' => $Tags,
            'AlbumPictures' => $albumPictures,
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

            $PicturesAlbums= $this->entityManager->getRepository(PicturesAlbum::class)->findAll();

            return $this->render('pictures/AlbumPicture.html.twig', [
                'AlbumPicture' => $AlbumPicture,
                'PicturesAlbums' => $PicturesAlbums,
            ]);

        }
    }
}