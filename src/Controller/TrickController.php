<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickPicture;
use App\Entity\TrickVideo;
use App\Entity\Comment;
use App\Form\EditTrickType;
use App\Form\TrickPicturesType;
use App\Form\TrickType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    // Liste des figures (Page d'accueil)
    
    /**
     * @Route("/")
     */
    public function list()
    {               
        // Calcul du nombre total de figures dans la base de données
        $totalTricks = $this->getDoctrine()
        ->getRepository(Trick::class)
        ->count([]);

        // Appel du template
        return $this->render('trick/list.html.twig', ['totalTricks' => $totalTricks]);        
    }

    // Ajout d'une figure

    /**
     * @Route("/trick/new")
     */
    public function new(Request $request)
    {
        // Objet Trick
        $trick = new Trick();
        
        // Génération du formulaire
        $form = $this->createForm(TrickType::class, $trick);

        // Traitement du formulaire (uniquement s'il a été soumis)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            foreach ($data->getPictures() as $picture) {

                // Suppression des champs d'images vide
                if ($picture->getFile() === null) {
                    $data->getPictures()->removeElement($picture);
                    continue;
                }
                $trick->addPicture($picture);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'La figure à bien été enregistrée.');

            // Redirection vers la liste des figures une fois la figure ajoutée
            return $this->redirectToRoute('app_trick_list');
        }

        // Appel du template
        return $this->render('trick/new.html.twig', ['form' => $form->createView()]);
    }

    // Visualisation d'une figure

    /**
     * @Route("/trick/view/{id}")
     */
    public function view(Trick $trick)
    {
        // Calcul du nombre total de commentaires correspondant à la figure dans la base de données
        $totalComments = $this->getDoctrine()
        ->getRepository(Comment::class)
        ->count(['trick' => $trick]);
        
        // Appel du template
        return $this->render('trick/view.html.twig', ['trick' => $trick, 'totalComments' => $totalComments]);
    }

    // Edition d'une figure

    /**
     * @Route("/trick/edit/{id}")
     */
    public function edit(Trick $trick, Request $request)
    {
        // Génération des formulaires
        $form = $this->createForm(EditTrickType::class, $trick);
        $formPictures = $this->createForm(TrickPicturesType::class);

        // Traitement des formulaires (uniquement s'ils ont étés soumis)
        $form->handleRequest($request);
        $formPictures->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($data->getPictures() as $picture) {
                $entityManager->persist($picture);
                $trick->addPicture($picture);
            }
            $entityManager->flush();

            // Redirection vers la page de visualisation de la figure qui viens d'être éditée
            return $this->redirectToRoute('app_trick_view', ['id' => $trick->getId()]);
        }

        if ($formPictures->isSubmitted() && $formPictures->isValid()) {
            $pictures = $formPictures->get('pictures')->getData();
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($pictures as $picture) {
                if (null === $picture) {
                    continue;
                }
                $trickPicture = new TrickPicture();
                $trickPicture->setFile($picture->getfile());
                $trickPicture->setTrick($trick);
                $entityManager->persist($trickPicture);
            }
            $entityManager->flush();

            // Redirection vers la page de visualisation de la figure qui viens d'être éditée
            return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
        }

        //Appel du template
        return $this->render('trick/edit.html.twig', ['form' => $form->createView(), 'formPictures' => $formPictures->createView(), 'trick' => $trick]);
    }

    // Suppression d'une figure

    /**
     * @Route("/trick/delete/{id}")
     */
    public function delete(Trick $trick)
    {
        // Suppression de la figure
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trick);
        $entityManager->flush();

        // Redirection vers la liste des figures une fois la figure supprimée
        return $this->redirectToRoute('app_trick_list');
    }

    // Suppression d'une image

    /**
     * @Route("/trick/delete/picture/{id}")
     */
    public function deletePicture(TrickPicture $picture)
    {
        // Récupération de l'Id de la figure liée à l'image
        $trick = $picture->getTrick()->getId();

        // Suppression de l'image
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($picture);
        $entityManager->flush();

        // Redirection vers la page d'édition de la figure à laquelle l'image appartenait
        return $this->redirectToRoute('app_trick_edit', ['id' => $trick]);
    }

    // Suppression d'une vidéo

    /**
     * @Route("/trick/delete/video/{id}")
     */
    public function deleteVideo(TrickVideo $video)
    {
        // Récupération de l'Id de la figure liée à la vidéo
        $trick = $video->getTrick()->getId();

        // Suppréssion de la vidéo
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($video);
        $entityManager->flush();

        // Redirection vers la page d'édition de la figure à laquelle la vidéo appartenait
        return $this->redirectToRoute('app_trick_edit', ['id' => $trick]);
    }

    // Chargement des figures sur la page listant toutes les figures
    
    /**
     * @Route("/trick/load/{lastTrick}")
     */
    public function loadMore($lastTrick = 0)
    {
        // Récupération de toutes les figures dans la base de données, classées de la plus récente à la plus ancienne
        $listTricks = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findBy([], ['id' => 'DESC']);

        //Appel du template
         return $this->render('trick/loadMore.html.twig', ['listTricks' => $listTricks, 'lastTrick' => $lastTrick]);
    }
}
