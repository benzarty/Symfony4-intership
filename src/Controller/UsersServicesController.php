<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Users;
use App\Form\ApprenantInscriptionType;
use App\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersServicesController extends AbstractController
{

    /**
     * @Route("/Servicepage1", name="Servicepage1", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->findBy(['idclient'=> null]);


        return $this->render('users_services/ViewOffers.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/showdetailuseroffre/{id}", name="showdetailuseroffre", methods={"GET"})
     */
    public function DetailCondidature(Offre $user,$id): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->findOneBy(['id'=> $id]);
        return $this->render('users_services/ShowDetailOffre.html.twig', [
            'users' => $users,
        ]);
    }












}
