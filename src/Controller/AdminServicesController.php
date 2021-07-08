<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminServicesController extends AbstractController
{
    /**
     * @Route("/admin/services", name="admin_services")
     */
    public function index(): Response
    {
        return $this->render('admin_services/index.html.twig');
    }
}
