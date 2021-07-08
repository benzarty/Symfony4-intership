<?php

namespace App\Controller;

use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


}
