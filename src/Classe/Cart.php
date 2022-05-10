<?php


namespace App\Classe;

use App\Entity\Event;
use App\Entity\MembershipRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class  Cart
{

    private  $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id) /* Ajoute un produit*/
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;  /* ajoute 1 produit */
        }
        else{
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart');
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

    public function getFull(): array
    {

        $cartComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity){
                $product_object = $this->entityManager->getRepository(MembershipRate::class)->findOneById($id);

                if(!$product_object){
                    $this->delete($id);
                    continue;
                }

                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }
}