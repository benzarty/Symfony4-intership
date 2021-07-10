<?php

namespace App\Controller;

use App\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreCrudController extends AbstractController
{

    /**
     * @Route("/GetOffre", name="GetOffre", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->findAll();

        return $this->render('offre_crud/index.html.twig', [
            'users' => $users,
        ]);
    }
}
