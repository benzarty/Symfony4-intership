<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ApprenantType;
use App\Form\Users1Type;
use App\Form\UsersType;
use App\Repository\UsersRepository;
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
     * @Route("/admin/services", name="admin_services")
     */
    public function indexhome(): Response
    {
        return $this->render('admin_services/index.html.twig');
    }

    /**
     * @Route("/profilsettingsadmin", name="profilsettingsadmin")
     * Method({"GET", "POST"})
     */
    public function profilsettingsadmin(Request $request, UserPasswordEncoderInterface $encoder)
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

            return $this->redirectToRoute('profilsettingsadmin');
        }

        return $this->render('admins_crud/ModifProfil.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @param UsersRepository $repo
     * @param Request $request
     * @Route("searchadmin", name="searchadmin")
     */
    public function RechercheAdmingo(UsersRepository $repo, Request $request)
    {
        $data = $request->get('search');
        $student = $repo->SearchAdmin($data);
        return $this->render('admins_crud/index.html.twig', ['users' => $student]);

    }


    /**
     * @Route("/GetAdmins", name="admins_crud_index", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findBy(['role' => 'admin']);

        return $this->render('admins_crud/index.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/Apprenant/new", name="new_admin")
     * Method({"GET", "POST"})
     */
    public function newAdmin(Request $request, UserPasswordEncoderInterface $encoder)
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
     * Method({"GET", "POST"})
     */
    public function editAdmin(Request $request, $id, UserPasswordEncoderInterface $encoder)
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

            return $this->redirectToRoute('admins_crud_index');
        }

        return $this->render('admins_crud/edit.html.twig', ['form' => $form->createView()]);
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
