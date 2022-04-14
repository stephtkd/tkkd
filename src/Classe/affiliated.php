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

        $affiliatedComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity){
                $member = $this->entityManager->getRepository(Member::class)->findOneById($id);

                if(!$member){
                    $this->delete($id);
                    continue;
                }

                $affiliatedComplete[] = [
                    'member' => $member,
                    'quantity' => $quantity
                ];
            }
        }
        return $affiliatedComplete;
    }

}