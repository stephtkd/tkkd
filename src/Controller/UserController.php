<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * Get all users.
     *
     * @Route("/users", name="get-all-users", methods={"GET"})
     */
    public function getAllUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        if ($users) {
            return $this->json($users);
        } else {
            return $this->json(null);
        }
    }

    /**
     * Add one user.
     *
     * @Route("/users/add", name="add-user", methods={"POST"})
     */
    public function addUser(Request $request): JsonResponse
    {
        $serializer = $this->get('serializer');
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user);
    }

    /**
     * Find one user by his id.
     *
     * @Route("/users/{id}", requirements={"id": "\d+"}, name="get-user", methods={"GET"})
     *
     * @param $id
     */
    public function getUserById($id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);
        if (empty($user)) {
            return $this->json(['message' => "Cet user n'existe pas"], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        return $this->json($user, RESPONSE::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Delete one user by his id.
     *
     * @Route("/users/{id}/delete", requirements={"id": "\d+"}, name="delete-one-user-by-id", methods={"DELETE"})
     *
     * @param $id
     */
    public function deleteUser($id, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);
        if (empty($user)) {
            return $this->json(['message' => 'User inconnu'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }

    /**
     * Get members linked to one user.
     *
     * @Route("/users/{id}/get-members/", requirements={"id": "\d+"}, name="get-members-by-user", methods={"GET"})
     *
     * @param $id
     */
    public function getMembersByUser($id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);
        if (empty($user)) {
            return $this->json(['message' => 'User inconnu'], Response::HTTP_NOT_FOUND, ['Content-Type', 'application/json']);
        }

        $userMembers = $user->getMembers();
        if (empty($userMembers)) {
            return $this->json(['message' => 'Pas d\'adhérent lié à cet utilisateur'], Response::HTTP_OK, ['Content-Type', 'application/json']);
        }

        return $this->json($userMembers, Response::HTTP_OK, ['Content-Type', 'application/json']);
    }
}
