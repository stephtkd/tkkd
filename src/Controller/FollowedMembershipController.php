<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowedMembershipController extends AbstractController
{
    #[Route('/account/followed-membership', name: 'app_followed_membership')]
    public function index(): Response
    {
        return $this->render('account/followedMembership.html.twig');
    }
}
