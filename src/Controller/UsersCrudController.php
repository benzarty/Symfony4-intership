<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersCrudController extends AbstractController
{
    /**
     * @Route("/Getusers", name="Getusers", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findBy(['role' => 'users']);

        return $this->render('users_crud/index.html.twig', [
            'users' => $users,
        ]);
    }
    /**
     * @Route("/editprofilusersgogo", name="editprofilusersgogo")
     * Method({"GET", "POST"})
     */
    public function editprofilusersgogo(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $article = $this->getDoctrine()->getRepository(Users::class)->find($user->getId());

        $form = $this->createForm(UsersType::class, $article);
        $form->add('Modifier Profil', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('imagedirectory'), $fileName);

            $hash = $encoder->encodePassword($article, $article->getPassword());
            $article->setPassword($hash);
            $article->setPhoto($fileName);


            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('info', 'Your Profile has been updated succesfully !!');

            return $this->redirectToRoute('editprofilusersgogo');
        }

        return $this->render('users_services/UpdateProfilUsersAfterLogin.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @param UsersRepository $repo
     * @param Request $request
     * @Route("searchusers", name="searchusers")
     */
    public function Rechercheusers(UsersRepository $repo, Request $request)
    {
        $data = $request->get('search');
        $student = $repo->SearchUsers($data);
        return $this->render('users_crud/index.html.twig', ['users' => $student]);

    }

    /**
     * @Route("/newUsers", name="newUsers")
     * Method({"GET", "POST"})
     */
    public function newUsers(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $article = new Users();
        $form = $this->createForm(UsersType::class, $article);
        $article->setRole("users");
        $article->setStatus("True");

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

            return $this->redirectToRoute('Getusers');
        }
        return $this->render('admins_crud/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("ShowUsers/{id}", name="ShowUsers", methods={"GET"})
     */
    public function show(Users $user): Response
    {
        return $this->render('users_crud/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/editUsers", name="editUsers", methods={"GET","POST"})
     * Method({"GET", "POST"})
     */
    public function editusers(Request $request, $id, UserPasswordEncoderInterface $encoder)
    {
        $article = $this->getDoctrine()->getRepository(Users::class)->find($id);

        $form = $this->createForm(UsersType::class, $article);
        $form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCodesecurity(1);

            $hash = $encoder->encodePassword($article, $article->getPassword());
            $article->setPassword($hash);


            $file = $form->get('photo')->getData();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('imagedirectory'), $fileName);


            $article->setPhoto($fileName);


            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('Getusers');
        }

        return $this->render('users_crud/edit.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/{id}", name="usersdelete", methods={"POST"})
     */
    public function deleteusers(Request $request, Users $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('Getusers', [], Response::HTTP_SEE_OTHER);
    }

}
