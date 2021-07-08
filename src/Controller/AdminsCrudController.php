<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ApprenantType;
use App\Form\Users1Type;
use App\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admins/crud")
 */
class AdminsCrudController extends AbstractController
{
    /**
     * @Route("/GetAdmins", name="admins_crud_index", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findAll();

        return $this->render('admins_crud/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name="admins_crud_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new Users();
        $form = $this->createForm(Users1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admins_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admins_crud/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);}


    /**
     * @Route("/Apprenant/new", name="new_admin")
     * Method({"GET", "POST"})
     */
    public function newAdmin(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $article = new Users();
        $form = $this->createForm(UsersType::class, $article);
        $article->setRole("apprenant");
        $form->add('ajouter', SubmitType::class);
        $article->setStatus("True");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //$file = $article->getPhoto();
            $file = $form->get('photo')->getData();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('imagedirectory'), $fileName);


            $article->setPhoto($fileName);


            $article->setCodesecurity(1);

            $hash = $encoder->encodePassword($article, $article->getPassword());
            $article->setPassword($hash);


            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admins_crud_index');
        }
        return $this->render('admins_crud/new.html.twig', ['form' => $form->createView()]);
    }
























    /**
     * @Route("/{id}", name="admins_crud_show", methods={"GET"})
     */
    public function show(Users $user): Response
    {
        return $this->render('admins_crud/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admins_crud_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Users $user): Response
    {
        $form = $this->createForm(Users1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admins_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admins_crud/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admins_crud_delete", methods={"POST"})
     */
    public function delete(Request $request, Users $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admins_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
