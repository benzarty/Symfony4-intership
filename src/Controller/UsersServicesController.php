<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Users;
use App\Form\ApprenantInscriptionType;
use App\Form\OffreUploadUserType;
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

    /**
     * @Route("/postulernow/{id}", name="postulernow")
     * Method({"GET", "POST"})
     */
    public function postulernow(Request $request,$id): Response
    {
        $userid = $this->get('security.token_storage')->getToken()->getUser();

        $users = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->findOneBy(['id' => $id]);

        $article = new Offre();
        $form = $this->createForm(OffreUploadUserType::class, $article);

        $article->setLibele($users->getLibele());
        $article->setCategorie($users->getCategorie());
        $article->setDatedebut($users->getDatedebut());
        $article->setDatefin($users->getDatefin());

        $article->setDescription($users->getDescription());


        $article->setIdclient($userid);




        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('cv')->getData();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('imagedirectory'), $fileName);


            $article->setCv($fileName);


            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('Servicepage1');
        }

            return $this->render('users_services/UploadcvUser.html.twig', [
                'form' => $form->createView()
            ]);


    }








}
