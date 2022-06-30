<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\HomeComment;
use App\Repository\EventRepository;
use App\Repository\EventSubscriptionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')] // page accueil
    public function index(): Response
    {
        $events= $this->entityManager->getRepository(Event::class)->findAll(); // affichage des cards evenements configurable dans l'easyAdmin
        $homeComment = $this->entityManager->getRepository(HomeComment::class)->findAll(); // affichage du texte configurable dans l'easyAdmin

        return $this->render('home/index.html.twig', [
            'events' => $events,
            'homeComment' => $homeComment
        ]);
    }


    #[Route('/export-event-csv', name: 'app_export_event_csv')] 
    public function exportEventCsv(
        EventRepository $eventRepository,
        SerializerInterface $serializer
    ): Response
    {
        $listEvent = $eventRepository->findAll();
        $list =[];
        $listResult = [];
        $listUnSet = [];

        $j = 0;

        foreach($listEvent as $value){
            // object to array [value,value, ...]
            $list[] = $serializer->serialize(
                $value,
                'csv',
                ['groups' => 'event']
            );

            $listUnSet[$j] = explode("\n",$list[$j]);//separate colum and row in each array

            //delete array which no utility
            if($j !== 0){
                unset($listUnSet[$j][0]);
            }elseif($j === 0){
                $listUnSet[$j][0] =  str_replace('"','',$listUnSet[$j][0]);
                unset($listUnSet[$j][2]);
            }

            if(count($listUnSet[$j]) === 2){
                unset($listUnSet[$j][2]);
            }

            $j++;
        }

        $z= 0;

        for($m = 0; $m < count($listUnSet); $m++){//format array for csv file

            foreach($listUnSet[$m] as $value){
                $listResult[$z] = $value;
                $listResult[$z] = explode(',',$listResult[$z]);
                
                $z++;
                
            }
        }
    

        $fp = fopen('export_event.csv', 'w');
        
        foreach ($listResult as $fields) {
            fputcsv($fp, $fields,";");
        }
        
        fclose($fp);

        $file = './export_event.csv';
        $response = new BinaryFileResponse($file);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'export_event.csv'
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    #[Route('/export-event-subscription-csv', name: 'app_export_event_subscription_csv')] 
    public function exportEventSubscriptionCsv(
        EventSubscriptionRepository $eventSubscriptionRepository,
        SerializerInterface $serializer
    ): Response
    {
        $listEventSubscription = $eventSubscriptionRepository->findAll();
        $list =[];
        $listResult = [];
        $listUnSet = [];

        $j = 0;

        foreach($listEventSubscription as $value){
            // object to array [value,value, ...]
            $list[] = $serializer->serialize(
                $value,
                'csv',
                ['groups' => 'export_event_subscription']
            );

            $listUnSet[$j] = explode("\n",$list[$j]);//separate colum and row in each array

            //delete array which no utility
            if($j !== 0){
                unset($listUnSet[$j][0]);
            }elseif($j === 0){
                $listUnSet[$j][0] =  str_replace('"','',$listUnSet[$j][0]);
                unset($listUnSet[$j][2]);
            }

            if(count($listUnSet[$j]) === 2){
                unset($listUnSet[$j][2]);
            }

            $j++;
        }

        $z= 0;

        for($m = 0; $m < count($listUnSet); $m++){//format array for csv file

            foreach($listUnSet[$m] as $value){
                $listResult[$z] = $value;
                $listResult[$z] = explode(',',$listResult[$z]);
                
                $z++;
                
            }
        }
    

        $fp = fopen('export_event_subscription.csv', 'w');
        
        foreach ($listResult as $fields) {
            fputcsv($fp, $fields,";");
        }
        
        fclose($fp);

        $file = './export_event_subscription.csv';
        $response = new BinaryFileResponse($file);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'export_event_subscription.csv'
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    #[Route('/import-csv', name: 'app_import_csv')] 
    public function importCsv(): Response
    {
        $targetPath = "upload/event/".basename($_FILES['inpFile']['name']);
        move_uploaded_file($_FILES['inpFile']['tmp_name'],$targetPath);

        $index = 0;
        $arrColumn = null;
        $arrayResult = [];
        $event = null;
        
        if (($fp = fopen($targetPath, "r")) !== FALSE) {

            while (($row = fgetcsv($fp, 1000, ",")) !== FALSE) {

                if($index === 0){
                    $arrColumn = explode(';',$row[0]);
                }else{
                    $arrRow = explode(';',$row[0]);

                    for($c = 0; $c < count($arrRow); $c++){
                        $arrayResult[$index][$arrColumn[$c]] = $arrRow[$c];
                    }
                }

                $index++;
            }
            fclose($fp);
        }
        //A DECOMMENTER POUR FAIRE FONCTIONNER
        //EN ATTENTE D'INFORMATIONS POUR ENREGISTRER CES DONNEES
        /*for($j = 1; $j < count($arrayResult); $j++){
            $event = new Event();

            foreach($arrayResult[$j] as $key => $value){

                if($key === Event::CLUB){
                    // $event->setClub($value);

                }else if($key === Event::NOM){
                    $event->setLastName($value);

                }else if($key === Event::PRENOM){
                    $event->setFirstName($value);

                }else if($key === Event::MF){

                    if($value === "M"){
                        $event->setSex("Homme");

                    }else{
                        $event->setSex("Femme");
            
                    }

                }else if($key === Event::NEE_LE){
                    $date = DateTime::createFromFormat('d/m/Y', $value);
                    $strDate = $date->format('Y-m-d');
                    $resDate = new DateTime($strDate);
                    $event->setBirthdate($resDate);

                }else if($key === Event::ADRESSE){
                    $event->setStreetAddress($value);

                }else if($key === Event::CODE_POSTAL){
                    $event->setPostalCode($value);

                }else if($key === Event::VILLE){
                    $event->setCity($value);

                }else if($key === Event::TEL){
                    $event->setPhoneNumber($value);

                }else if($key === Event::TEL_ACCIDENT){
                    $event->setEmergencyPhone($value);

                }else if($key === Event::NATIONALITE){
                    $event->setNationality($value);

                }else if($key === Event::EMAIL){
                    $event->setEmail($value);

                }else if($key === Event::GRADE){
                    $event->setLevel($value);

                }
            }

            $this->entityManager->persist($event);

        }*/
        

        if($event){
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_home');
    }

}
