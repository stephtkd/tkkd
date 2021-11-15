<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Adherent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ApiController extends AbstractController
{


    //********Routes pour l'entité User*********************

    //Liste de tous les utilisateurs
    /**
     * @Route("/users", name="get-all-users", methods={"GET"})
     */
    public function getAllUsers(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        return $this->json($users);
    }

    //Ajouter un utilisateur
    /**
     * @Route("/user/add", name="addUser", methods={"POST"})
     */
    public function addUser(Request $request){
        $serializer = $this->get('serializer');
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json($user);
    }

    //Trouver un utilisateur via un ID
    /**
     * @Route("/user/{id}", requirements={"id": "\d+"}, name="get-one-user-by-id", methods={"GET"})
     * @ParamConverter("user", class="App:User")
     */
    public function userById($user){
        return $this->json($user);
    }

    //Supprimer un utilisateur via ID
    /**
     * @Route("/user/delete/{id}", requirements={"id": "\d+"}, name="delete-one-user-by-id", methods={"DELETE"})
     * @ParamConverter("user", class="App:User", options={"id": "id"})
     */
    public function deleteUser($user){
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->merge($user);
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this->json(null, 204);
              
    }

    //*********Routes pour l'entité Adherent*******************

    //Liste de tous les adherents
    /**
     * @Route("/adherents", name="get-all-adherents", methods={"GET"})
     */
    public function getAllAdherents(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $adherent = $repository->findAll();
        return $this->json($adherent);
    }

    //Ajouter un Adherent
    /**
     * @Route("/adherent/add", name="addAdherent", methods={"POST"})
     */
    public function addAdherent(Request $request){
        $serializer = $this->get('serializer');
        $adherent = $serializer->deserialize($request->getContent(), Adherent::class, 'json');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($adherent);
        $entityManager->flush();
        return $this->json($adherent);
    }

    //Trouver un Adherent via un ID
    /**
     * @Route("/adherent/{id}", requirements={"id": "\d+"}, name="get-one-adherent-by-id", methods={"GET"})
     * @ParamConverter("adherent", class="App:Adherent")
     */
    public function adherentById($adherent){
        return $this->json($adherent);
    }
    
    //Supprimer un utilisateuadherentr via ID
    /**
     * @Route("/adherent/delete/{id}", requirements={"id": "\d+"}, name="delete-one-adherent-by-id", methods={"DELETE"})
     * @ParamConverter("adherent", class="App:Adherent", options={"id": "id"})
     */
    public function deleteAdherent($adherent){
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->merge($adherent);
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this->json(null, 204);
    }

}


