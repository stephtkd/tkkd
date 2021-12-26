<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * Get all posts.
     *
     * @Route("/posts", name="get-all-posts", methods={"GET"})
     */
    public function getPosts(PostRepository $postRepository): JsonResponse
    {
        $posts = $postRepository->findAll();
        if (empty($posts)) {
            return $this->json(['message' => 'Aucun post'], Response::HTTP_OK, ['Content-Type', 'application/json']);
        } else {
            return $this->json($posts, Response::HTTP_OK, ['Content-Type', 'application/json']);
        }
    }

    /**
     * Find one post by its id.
     *
     * @Route("/posts/{id}", requirements={"id": "\d+"}, name="get-post", methods={"GET"})
     *
     * @param $id
     */
    public function getPost($id, PostRepository $postRepository): JsonResponse
    {
        $post = $postRepository->find($id);
        if (empty($post)) {
            return $this->json(['message' => 'Post inconnu'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        return $this->json($post, RESPONSE::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Add post.
     *
     * @Route("/posts/add", name="add-post", methods={"POST"})
     */
    public function addPost(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $serializer = $this->get('serializer');
        /** @var Post $post */
        $post = $serializer->deserialize($request->getContent(), Post::class, 'json');
        $now = new DateTime('NOW');
        $post->setPublicationDate($now);

        $errors = $validator->validate($post);
        if ($errors && count($errors)) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, ['Content-Type', 'application/json']);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json($post, Response::HTTP_CREATED, ['Content-Type', 'application/json']);
    }

    /**
     * Delete post.
     *
     * @Route("/posts/{id}/delete", requirements={"id": "\d+"}, name="delete-post", methods={"DELETE"})
     * @ParamConverter("post", class="App:Post", options={"id": "id"})
     *
     * @param $id
     */
    public function deletePost($id, EntityManagerInterface $entityManager, PostRepository $postRepository): JsonResponse
    {
        $post = $postRepository->find($id);
        if (empty($post)) {
            return $this->json(['message' => 'Post inconnu'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $entityManager->remove($post);
        $entityManager->flush();

        return $this->json(null, RESPONSE::HTTP_NO_CONTENT, ['Content-Type', 'application/json']);
    }

    /**
     * Find posts by type.
     *
     * @Route("/posts/{type}", name="find-posts-by-type", methods={"GET"})
     *
     * @param $type
     */
    public function findPostsbyType($type, EntityManagerInterface $entityManager): JsonResponse
    {
        $query = $entityManager->createQuery(
            'SELECT p
                 FROM App\Entity\Post p
                 WHERE p.type = :type
                 ORDER BY p.publicationDate DESC
                 '
        )->setParameter('type', $type);
        $posts = $query->getResult();
        if (empty($posts)) {
            return $this->json(['message' => 'Type inconnu'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        return $this->json($posts, Response::HTTP_OK);
    }

    /**
     * Update post.
     *
     * @Route("/posts/{id}/update", requirements={"id": "\d+"}, name="udpdate-post", methods={"PUT"})
     *
     * @param $id
     */
    public function updatePost($id, Request $request, PostRepository $postRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $post = $postRepository->find($id);

        if (empty($post)) {
            return $this->json(['message' => "Ce post n'existe pas"], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $form = $this->createForm(PostType::class, $post);

        $data = json_decode($request->getContent(), true);

        $form->submit($data, false);

        if ($form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->json($post, Response::HTTP_OK, ['Content-Type', 'application/json']);
        } else {
            return $this->json($form, Response::HTTP_BAD_REQUEST, ['Content-Type', 'application/json']);
        }
    }
}
