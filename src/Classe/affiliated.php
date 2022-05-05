<?php
namespace App\Classe;



use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class affiliated
{
    private  $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id) /* Ajoute un membre*/
    {
        $affiliated = $this->session->get('affiliated', []);

        if (!empty($affiliated[$id])) {
            $affiliated[$id]++; // ajoute 1 produit
        }
        else{
            $affiliated[$id] = 1;
        }
        $this->session->set('affiliated', $affiliated);
    }

    public function get()
    {
        return $this->session->get('affiliated');
    }

    public function remove()
    {
        return $this->session->remove('affiliated');
    }

    public function delete($id) //supprime un membre
    {
        $affiliated = $this->session->get('affiliated', []);

        unset($affiliated[$id]);

        return $this->session->set('affiliated', $affiliated);
    }

    public function decrease($id) // Enleve un membre
    {
        $affiliated = $this->session->get('affiliated', []);

        if ($affiliated[$id] > 1) {
            $affiliated[$id]--;
        }
        else {
            unset($affiliated[$id]);
        }
        return $this->session->set('affiliated', $affiliated);
    }

    public function getFull(): array
    {

        $cartComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity){
                $affiliated_object = $this->entityManager->getRepository(Member::class)->findOneById($id);

                if(!$affiliated_object){
                    $this->delete($id);
                    continue;
                }

                $affiliatedComplete[] = [
                    'affiliated' => $affiliated_object,
                    'quantity' => $quantity
                ];
            }
        }
        return $affiliatedComplete;
    }
}