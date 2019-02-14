<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    // Ajout d'un commentaire

    /**
     * @Route("/comment/{trickId}")
     */
    public function new($trickId, Request $request)
    {
        // Récupération de la figure liée au commentaire
        $trick = $this->getDoctrine()
        ->getRepository(Trick::class)
        ->find($trickId);

        // Récupération de l'utilisateur qui poste le commentaire
        $user = $this->getUser();

        // Objet Comment
        $comment = new Comment();
        $comment->setTrick($trick);
        $comment->setUser($user);

        // Génération du formulaire
        $form = $this->createForm(CommentType::class, $comment);

        // Traitement du formulaire (uniquement s'il a été soumis)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Ajout du commentaire dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirection vers la page de visualisation de la figure liée au commentaire qui viens d'être ajouté
            return $this->redirectToRoute('app_trick_view', ['id' => $trick->getId()]);
        }
        
        // Appel du template
        return $this->render('comment/new.html.twig', ['comment' => $comment, 'form' => $form->createView()]);

    }

    // Chargement des commentaires

    /**
     * @Route("/comment/load/{trickId}-{lastComment}")
     */
    public function loadMore($trickId = 0, $lastComment = 0)
    {
        // Récupération de tous les commentaires de la figure dans la base de données, classés du plus récent au plus ancien
        $listComments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(['trick' => $trickId], ['id' => 'DESC']);

        //Appel du template
         return $this->render('comment/loadMore.html.twig', ['listComments' => $listComments, 'lastComment' => $lastComment]);
    }
}
