<?php

namespace App\Controller;

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
     * @Route("/users/services", name="users_services")
     */
    public function index(): Response
    {
        return $this->render('users_services/HomeUsersAfterLogin.twig'
        );
    }

    /**
     * @Route("/RegisterUsers", name="RegisterUsers")
     */
    public function RegisterUsers(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setStatus("False");
            $user->setRole("users");
            $file = $form->get('photo')->getData();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('imagedirectory'), $fileName);


            $user->setPhoto($fileName);



            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('info', 'Your request has been added succesfully !!');

            return $this->redirectToRoute('RegisterUsers');
        }
        return $this->render('users_services/RegisterUsers.html.twig', ['form' => $form->createView()]);
    }





}
