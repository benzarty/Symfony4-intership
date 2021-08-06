<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Users;
use App\Form\OffreAdminType;
use App\Form\OffreType;
use App\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class OffreCrudController extends AbstractController
{
    /**
     * @Route("/offre_show/{id}", name="offre_show", methods={"GET"})
     */
    public function showOffre(Offre $user): Response
    {
        return $this->render('offre_crud/show.html.twig', [
            'user' => $user,
        ]);
    }




    /**
     * @Route("/GetOffre", name="GetOffre", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->findBy(['idclient'=> null]);

        return $this->render('offre_crud/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/OffreNew", name="OffreNew")
     * Method({"GET", "POST"})
     */
    public function newOffre(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $article = new Offre();
        $form = $this->createForm(OffreAdminType::class, $article);

        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //$file = $article->getPhoto();

            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('GetOffre');
        }
        return $this->render('offre_crud/new.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/edditoffre/{id}", name="edditoffre", methods={"GET","POST"})
     * Method({"GET", "POST"})
     */
    public function edditoffre(Request $request, $id, UserPasswordEncoderInterface $encoder)
    {
        $article = $this->getDoctrine()->getRepository(Offre::class)->find($id);

        $form = $this->createForm(OffreAdminType::class, $article);
        $form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {




            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('GetOffre');
        }

        return $this->render('offre_crud/edit.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("offre_crud_delete/{id}", name="offre_crud_delete", methods={"POST"})
     */
    public function delete(Request $request, Offre $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('GetOffre', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/seecondidatures", name="seecondidatures", methods={"GET","POST"})
     */
    public function indexcondidature(): Response
    {

        $users = $this->getDoctrine()->getRepository(Offre::class)->findnonull();




        return $this->render('offre_crud/SeeCondidature.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("condidatureeee_crud_delete/{id}", name="condidatureeee_crud_delete", methods={"POST"})
     */
    public function deleteCondidature(Request $request, Offre $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('seecondidatures', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/DetailCondidature/{id}", name="DetailCondidature", methods={"GET"})
     */
    public function DetailCondidature(Offre $user): Response
    {
        return $this->render('offre_crud/ShowCondidaturedetail.html.twig', [
            'user' => $user,
        ]);
    }


}
