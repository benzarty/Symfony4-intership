<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SecurityController extends AbstractController
{

    /**
     * @Route("/", name="homee")
     */
    public function indexhome(): Response
    {
        return $this->render('centrale/AllUsersMainpage.html.twig');
    }


    /**
     * @Route("/users/services", name="users_services")
     */
    public function index(): Response
    {
        return $this->render('users_services/HomeUsersAfterLogin.twig'
        );
    }

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param AuthenticationUtils $utils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, AuthenticationUtils $utils): \Symfony\Component\HttpFoundation\Response
    {

        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        return $this->render('security/LoginLogin.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);


    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('security/LoginLogin.html.twig');
    }


    /**
     * @Route("/home", name="home")
     */
    public function home(Request $request, AuthenticationUtils $utils): \Symfony\Component\HttpFoundation\Response
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (($user->getRole() == "users") and ($user->getStatus() == "True"))
            return $this->render('users_services/HomeUsersAfterLogin.twig');

        else if ($user->getRole() == "admin")
            return $this->redirectToRoute('admins_crud_index');

        else
            $this->addFlash(
                'info', 'Your have entred wrong Password or your account is blocked Sir or maybe you dont have access  !!');
        return $this->redirectToRoute('login');


    }

    /**
     * @Route("/RegisterUsersnow", name="RegisterUsersnow")
     * Method({"GET", "POST"})
     */
    public function registerusernow(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {
        $article = new Users();
        $form = $this->createForm(UsersType::class, $article);
        $article->setRole("admin");
        $form->add('ajouter', SubmitType::class);
        $article->setStatus("True");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //$file = $article->getPhoto();
            $file = $form->get('photo')->getData();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('imagedirectory'), $fileName);


            $article->setPhoto($fileName);


            $hash = $encoder->encodePassword($article, $article->getPassword());
            $article->setPassword($hash);


            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('info', 'Your request has been added succesfully !!');


            $message = (new \Swift_Message('Registration Email'))
                ->setFrom('m.benzarti.1996@gmail.com')
                ->setTo($article->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'users_services/registrationMail.html.twig'

                    ),
                    'text/html'
                );

            $mailer->send($message);


            return $this->redirectToRoute('RegisterUsersnow');
        }
        return $this->render('users_services/RegisterUsers.html.twig', ['form' => $form->createView()]);
    }


}
