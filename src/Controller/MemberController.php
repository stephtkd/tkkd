<?php

namespace App\Controller;

use App\Classe\affiliated;
use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\EventRepository;
use App\Repository\MemberRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shuchkin\SimpleXLSX;
use Symfony\Component\Serializer\SerializerInterface;

class MemberController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private EventRepository $eventRepository;


    public function __construct(EntityManagerInterface $entityManager, EventRepository $eventRepository)
    {
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
    }

    /**
     * member file.
     *
     * @Route("/account/members", name="account_member") // affichage du recapitulatif du fomulaire d'inscription d'adhesion
     */
    public function index(): Response
    {
        $adhesion = $this->eventRepository->findActualAdhesion();
        
        return $this->render('account/member.html.twig', [
            'adhesion' => $adhesion
        ]);

    }

    #[Route('/account/members/export-csv', name: 'account_member_export_csv')] 
    public function exportCsv(
        MemberRepository $memberRepository,
        SerializerInterface $serializer
    ): Response
    {
        $listMember = $memberRepository->findAll();
        $list =[];
        $listResult = [];
        $listUnSet = [];

        $j = 0;

        foreach($listMember as $value){
            // object to array [value,value, ...]
            $list[] = $serializer->serialize(
                $value,
                'csv',
                ['groups' => 'member']
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
    

        $fp = fopen('file.csv', 'w');
        
        foreach ($listResult as $fields) {
            fputcsv($fp, $fields,";");
        }
        
        fclose($fp);


        return $this->redirectToRoute('account_member');
    }

    #[Route('/account/members/import-csv', name: 'account_member_import_csv')] 
    public function importCsv(): Response
    {
        $targetPath = "upload/member/".basename($_FILES['inpFile']['name']);
        move_uploaded_file($_FILES['inpFile']['tmp_name'],$targetPath);

        $index = 0;
        $arrColumn = null;
        $arrayResult = [];
        $member = null;
        
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

        for($j = 1; $j < count($arrayResult); $j++){
            $member = new Member();

            foreach($arrayResult[$j] as $key => $value){

                if($key === Member::CLUB){
                    // $member->setClub($value);

                }else if($key === Member::NOM){
                    $member->setLastName($value);

                }else if($key === Member::PRENOM){
                    $member->setFirstName($value);

                }else if($key === Member::MF){

                    if($value === "M"){
                        $member->setSex("Homme");

                    }else{
                        $member->setSex("Femme");
            
                    }

                }else if($key === Member::NEE_LE){
                    $date = DateTime::createFromFormat('d/m/Y', $value);
                    $strDate = $date->format('Y-m-d');
                    $resDate = new DateTime($strDate);
                    $member->setBirthdate($resDate);

                }else if($key === Member::ADRESSE){
                    $member->setStreetAddress($value);

                }else if($key === Member::CODE_POSTAL){
                    $member->setPostalCode($value);

                }else if($key === Member::VILLE){
                    $member->setCity($value);

                }else if($key === Member::TEL){
                    $member->setPhoneNumber($value);

                }else if($key === Member::TEL_ACCIDENT){
                    $member->setEmergencyPhone($value);

                }else if($key === Member::NATIONALITE){
                    $member->setNationality($value);

                }else if($key === Member::EMAIL){
                    $member->setEmail($value);

                }else if($key === Member::GRADE){
                    $member->setLevel($value);

                }
            }

            $this->entityManager->persist($member);

        }
        

        if($member){
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('account_member');
    }

    #[Route('/account/members/form', name: 'account_member_add')] //affichage du formulaire d'adhesion, d'ajout de membre
    public function add(Request $request, affiliated $affiliated): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $file = $form->get('photoName')->getData();

                $member = $this->setFilesToMember($member, $file);

                $member->setMembershipState('Paiement en attente');
                $member->setResponsibleAdult($this->getUser());

                $this->entityManager->persist($member);
                $this->entityManager->flush();

                $adhesion = $this->eventRepository->findActualAdhesion();

                if (is_null($adhesion)) {
                    return $this->redirectToRoute('account_member');
                }
                return $this->redirectToRoute('app_subscription', ['id' => $adhesion->getId()]);
        }

        return $this->render('account/memberForm.html.twig', [
            'form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }


    #[Route('/account/members-edit/{id}', name: 'account_member_edit')] //affichage du formulaire d'adhesion, de modification du membre
    public function edit(Request $request, $id): Response
    {
        $member = $this->entityManager->getRepository(Member::class)->findOneById($id);

        if(!$member || $member->getResponsibleAdult() != $this->getUser()) {
            return $this->redirectToRoute('account_member');
        }

        if (!is_null($member->getPhotoName())) {
            $member->setPhotoName('./upload/member/'.$member->getPhotoName());
        }

        $form = $this->createForm(MemberType::class, $member);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photoName')->getData();

            $member = $this->setFilesToMember($member, $file);

            $this->entityManager->persist($member);
            $this->entityManager->flush();
            return $this->redirectToRoute('account_member');
        }

        return $this->render('account/memberForm.html.twig', [
            'form' => $form->createView(),
            'user' => $member
        ]);
    }

    #[Route('/account/members-delete/{id}', name: 'account_member_delete')] //suppression de membre
    public function delete($id): Response
    {
        $member = $this->entityManager->getRepository(Member::class)->findOneById($id);

        if($member && $member->getResponsibleAdult() == $this->getUser()) {
            $this->entityManager->remove($member);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('account_member');

    }

    private function setFilesToMember($member, $file) {
        if (!is_null($file)) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('member_directory'),
                $fileName);
            $member->setPhotoName($fileName);
        } else if (!is_null($member->getPhotoName())) {
            $member->setPhotoName(str_replace("./upload/member/", "",$member->getPhotoName()));
        }

        return $member;
    }

}
