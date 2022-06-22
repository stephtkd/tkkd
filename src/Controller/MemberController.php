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

    const CLUB="CLUB";
    const NOM="NOM";
    const PRENOM="PRENOM";
    const MF="M/F";
    const NEE_LE="NE(E) LE";
    const ADRESSE="ADRESSE";
    const CODE_POSTAL="CODE POSTAL";
    const VILLE="VILLE";
    const TEL="TEL";
    const TEL_ACCIDENT="TEL ACCIDENT";
    const NATIONALITE="NATIONALITE";
    const EMAIL="EMAIL";
    const GRADE="GRADE TAEKWONKIDO";
    
    

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
        $adhesion = $this->eventRepository->findActualAdhesion();

        $listMember = $memberRepository->findAll();
        $list =[];
        $listResult = [];
        $listUnSet = [];

        foreach($listMember as $value){// object to array [value,value, ...]

            $list[] = $serializer->serialize(
                $value,
                'csv',
                ['groups' => 'member']
            );
        }

        for($j = 0; $j < count($list); $j++){//separate colum and row in each array
            $listUnSet[$j] = explode("\n",$list[$j]);
        }

        $i = 0;

        foreach($listUnSet as $value){//delete array which no utility

            if($i !== 0){
                unset($listUnSet[$i][0]);
            }

            if(count($value) === 3){
                unset($listUnSet[$i][2]);
            }

            $i++;
        }

        $z= 0;

        for($m = 0; $m < count($listUnSet); $m++){

            foreach($listUnSet[$m] as $value){
                $listResult[$z] = $value;
                $z++;
            }
        }
        
        for($m = 0; $m < count($listResult); $m++){
            $listResult[$m] = explode(',',$listResult[$m]);
        }

        $fp = fopen('file.csv', 'w');
        
        foreach ($listResult as $fields) {
            fputcsv($fp, $fields,";");
        }
        
        fclose($fp);


        return $this->render('account/member.html.twig', [
            'adhesion' => $adhesion
        ]);
    }

    #[Route('/account/members/import-csv', name: 'account_member_import_csv')] 
    public function importCsv(): Response
    {
        $adhesion = $this->eventRepository->findActualAdhesion();

        $targetPath = "upload/".basename($_FILES['inpFile']['name']);
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

                if($key === self::CLUB){
                    // $member->setClub($value);

                }else if($key === self::NOM){
                    $member->setLastName($value);

                }else if($key === self::PRENOM){
                    $member->setFirstName($value);

                }else if($key === self::MF){

                    if($value === "M"){
                        $member->setSex("Homme");

                    }else{
                        $member->setSex("Femme");
            
                    }

                }else if($key === self::NEE_LE){
                    $date = DateTime::createFromFormat('d/m/Y', $value);
                    $strDate = $date->format('Y-m-d');
                    $resDate = new DateTime($strDate);
                    $member->setBirthdate($resDate);

                }else if($key === self::ADRESSE){
                    $member->setStreetAddress($value);

                }else if($key === self::CODE_POSTAL){
                    $member->setPostalCode($value);

                }else if($key === self::VILLE){
                    $member->setCity($value);

                }else if($key === self::TEL){
                    $member->setPhoneNumber($value);

                }else if($key === self::TEL_ACCIDENT){
                    $member->setEmergencyPhone($value);

                }else if($key === self::NATIONALITE){
                    $member->setNationality($value);

                }else if($key === self::EMAIL){
                    $member->setEmail($value);

                }else if($key === self::GRADE){
                    $member->setLevel($value);

                }
            }

            $this->entityManager->persist($member);

        }
        

        if($member){
            $this->entityManager->flush();
        }

        return $this->render('account/member.html.twig', [
            'adhesion' => $adhesion
        ]);
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
