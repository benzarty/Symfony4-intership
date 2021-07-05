<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CentraleController extends AbstractController
{
    /**
     * @Route("/", name="homee")
     */
    public function index(): Response
    {
        return $this->render('centrale/AllUsersMainpage.html.twig');
    }
}
