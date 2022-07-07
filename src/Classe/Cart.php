<?php

namespace App\Classe;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class  Cart
{
    private SessionInterface $session;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
        $this->entityManager = $entityManager;
    }

    public function add($data) /* Ajoute un produit*/
    {
        $cart = $this->session->get('cart', []);

        array_push($cart, $data);

        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart', []);
    }

    public function getOne($id)
    {
        $cart = $this->session->get('cart', []);
        return $cart[$id];
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function decrease($id) /* Enleve un produit*/
    {
        $cart = $this->session->get('cart', []);

        if ($cart[$id] > 1) {
            $cart[$id]--;
        }
        else {
            unset($cart[$id]);
        }
        return $this->session->set('cart', $cart);
    }

    public function persistCart($session_id = null) 
    {
        $cart = $this->session->get('cart', []);

        if ($cart != []) {
            foreach ($this->get() as $subscription) {
                $subscription->getPayment()->setReference($session_id);
                $this->entityManager->merge($subscription);
            }
        }

        $this->entityManager->flush();
        $this->remove();
    }
}