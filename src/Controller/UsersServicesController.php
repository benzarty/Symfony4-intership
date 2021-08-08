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

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Administrateur;
use App\Entity\Apprenant;
use App\Entity\Formation;

use App\Form\AdminFomType;
use App\Form\ProfesseurType;

use Egulias\EmailValidator\Validation\RFCValidation;


use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


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
     * @Route("/PasswordForgottenno", name="PasswordForgottenno", methods={"GET"})
     */
    public function PasswordForgottenno(): Response
    {

        return $this->render('users_services/ResetPassword.html.twig'
        );
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
            $article->setStatus(0);


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



    /**
     * @Route("/email2",name="email2")
     */

    public function SendMail(\Swift_Mailer $mailer)
    {


        $message = (new \Swift_Message('Notification Email'))
            ->setFrom('m.benzarti.1996@gmail.com')
            ->setTo('m.benzarti.1996@gmail.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'users_services/registrationMail.html.twig'
                ),
                'text/html'
            );

        $mailer->send($message);

        return $this->render('admin_services/backendAdmin.html.twig');
    }

    /**
     * @Route("/email",name="email")
     */


    public function mailnow(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('m.benzarti.1996@gmail.com')
            ->setTo('m.benzarti.1996@gmail.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'users_services/registrationMail.html.twig'

                ),
                'text/html'
            )


        ;

        $mailer->send($message);

        return $this->render('admin_services/backendAdmin.html.twig');
    }



}
